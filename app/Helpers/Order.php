<?php

namespace App\Helpers;

use App\Http\Controllers\Settings;
use App\Models\ItemBound;
use App\Models\Item;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class Order
{
    static function gen_number($type='outbound')
    {
        $lastOrder = ItemBound::where('type', $type)->orderBy('id', 'DESC')->get('order_number')->first();
        $order_number = ($lastOrder) ? $int = (int) filter_var($lastOrder->order_number, FILTER_SANITIZE_NUMBER_INT) : 0;
        $order_number ++;
        $order_number = str_pad($order_number, 6, '0', STR_PAD_LEFT);
        $prefix = ($type == 'inbound') ? 'IN' : 'OUT';
        return $prefix.$order_number;
    }

    static function get_customer($order_id)
    {
        $order = ItemBound::find($order_id);
        $customer = User::find($order->customer)->toArray();
        return $customer;
    }

    static function get_items($order_id=null)
    {
        $items = Item::all()->toArray();
        $items = array_reduce($items, function($carry, $item){
            $carry[$item['id']] = $item;
            return $carry;
        });
        
        $order_items = $items;
        if ($order_id) {
            $order_items = [];
            $order = ItemBound::find($order_id);
            $items_data = unserialize($order->item);
            foreach ($items_data as $item) {
                $order_items[$item['item']] = $item;
            }
            
            $item_prices = self::get_item_prices();
            foreach ($order_items as $id => $item) {
                $order_items[$id]['price'] = $item_prices[$id];
                $order_items[$id]['item_cost'] = $item['qty'] * $item_prices[$id];
                if ($order_id) {
                    $order_items[$id]['item_name'] = $items[$id]['item'];
                }
            }
        }
        return $order_items;
    }

    static function get_inbound_graphs()
    {
        $inbounds = ItemBound::select("item")->where("type", "inbound")->get()->toArray();
        $total_balance = 0;
        if (!empty($inbounds)) {
            foreach ($inbounds as $inbound) {
                $items = unserialize($inbound['item']);
                foreach ($items as $item) {
                    $total_balance += $item['qty'];
                }                
            }
        }  
        DB::enableQueryLog();
        $weekly = ItemBound::selectRaw('item as this_week')->whereRaw("extract(week from item_bounds.created_at) = '".abs(date('W'))."' AND item_bounds.type='inbound'")->get()->toArray();
        $query = DB::getQueryLog();
        print_r($weekly);
        echo '</pre>';
        die();
        return $total_balance;    
    }

    static function get_item_prices()
    {
        $items = Item::select(['id', 'price'])->get()->toArray();
        $items = array_reduce($items, function($carry, $item){
            $carry[$item['id']] = $item['price'];
            return $carry;
        });
        return $items;
    }

    static function get_order_summary()
    {        
        $items = self::get_item_prices();
        $daily = ItemBound::select('item')->whereRaw("CAST(item_bounds.created_at AS DATE) = CAST('".date('Y-m-d')."' AS DATE) AND item_bounds.type='outbound'")->get()->toArray();
        $daily = array_reduce($daily, function($carry, $order) use($items){
            $orders = unserialize($order['item']);
            foreach ($orders as $order_item) {
                $item_id = $order_item['item'];
                $item_qty = $order_item['qty'];
                $item_price = array_key_exists($item_id, $items) ? $items[$item_id] : 0;
                $carry += $item_qty * $item_price;
            }
            return $carry;
        });

        $weekly = ItemBound::select('item')->whereRaw("extract(week from item_bounds.created_at) = '".abs(date('W'))."' AND item_bounds.type='outbound'")->get()->toArray();
        $weekly = array_reduce($weekly, function($carry, $order) use($items){
            $orders = unserialize($order['item']);
            foreach ($orders as $order_item) {
                $item_id = $order_item['item'];
                $item_qty = $order_item['qty'];
                $item_price = array_key_exists($item_id, $items) ? $items[$item_id] : 0;
                $carry += $item_qty * $item_price;
            }
            return $carry;
        });

        $monthly = ItemBound::select('item')->whereRaw("extract(month from item_bounds.created_at) = '".date('m')."' AND item_bounds.type='outbound'")->get()->toArray();
        $monthly = array_reduce($monthly, function($carry, $order) use($items){
            $orders = unserialize($order['item']);
            foreach ($orders as $order_item) {
                $item_id = $order_item['item'];
                $item_qty = $order_item['qty'];
                $item_price = array_key_exists($item_id, $items) ? $items[$item_id] : 0;
                $carry += $item_qty * $item_price;
            }
            return $carry;
        });
        $total = ItemBound::select('item')->where('type', 'outbound')->get()->toArray();
        $total = array_reduce($total, function($carry, $order) use($items){
            $orders = unserialize($order['item']);
            foreach ($orders as $order_item) {
                $item_id = $order_item['item'];
                $item_qty = $order_item['qty'];
                $item_price = array_key_exists($item_id, $items) ? $items[$item_id] : 0;
                $carry += $item_qty * $item_price;
            }
            return $carry;
        });

        $daily = $daily > 0 ? $daily : 0;
        $weekly = $weekly > 0 ? $weekly : 0;
        $monthly = $monthly > 0 ? $monthly : 0;
        $total = $total > 0 ? $total : 0;

        $summary = [
            'daily' => $daily,
            'weekly' => $weekly,
            'monthly' => $monthly,
            'total' => $total
        ];
        return $summary;
    }

    public static function getItemBoundsByItemID($item_id, $type='')
    {
        $item1 = str_replace('}', '', str_replace('a:1:', '', serialize(['item' => $item_id])));
        $item2 = str_replace('}', '', str_replace('a:1:', '', serialize(['item' => "{$item_id}"])));
        $add_where = !empty($type) ? " AND type = '{$type}'" : "";

        $items_bounds = ItemBound::whereRaw("(item LIKE '%{$item1}%' OR item LIKE '%{$item2}%') {$add_where} ORDER BY id")->get()->toArray();
        
        $inbound_data = [];
        if (!empty($items_bounds)) {
            foreach ($items_bounds as $inbound) {
                $inbound_item = unserialize($inbound['item']);
                if (array_key_exists('item', $inbound_item[0]) && $inbound_item[0]['item'] == $item_id) {
                    $inbound_data = $inbound_item[0];
                }
            }
        }
        return $inbound_data;
    }

    public static function getCriticalItems()
    {
        $items = Item::all();
        $items_chart = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $inbound = self::getItemBoundsByItemID($item->id, 'inbound');
                $inbound_qty = !empty($inbound) ? $inbound['qty'] : 0;
                if ($inbound_qty) {
                    $balance_percentage = round(($item->balance/$inbound_qty) * 100);
                    if ($balance_percentage <= 20) {
                        $items_chart[$item->id] = [
                            'id' => $item->id,
                            'name' => $item->item,
                            'percentage' => $balance_percentage,
                            'remaining' => $item->balance
                        ];
                    }
                }
            }
        }
        return $items_chart;
    }
}