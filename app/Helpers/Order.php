<?php

namespace App\Helpers;

use App\Models\ItemBound;
use App\Models\Item;
use App\Models\User;
use Exception;

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

    static function get_items($order_id=null)
    {
        $items = [];
        if ($order_id) {
            $order = ItemBound::find($order_id);
            $items_data = unserialize($order->item);
            foreach ($items_data as $item) {
                $items[$item['item']] = $item;
            }
        } else {
            $items = Item::all()->toArray();
            $items = array_reduce($items, function($carry, $item){
                $carry[$item['id']] = $item;
                return $carry;
            });
        }        
        return $items;
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
}