<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\Order;
use App\Models\Item;
use App\Models\User;

class ItemBoundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        $type = 'outbound';
        $items = Item::get('id')->toArray();
        $customer = User::where('role', 'customer')->get('id')->first();
        $customer_id = !empty($customer)? $customer->id : 0;
        $admin = User::where('role', 'administrator')->get('id')->first();
        $admin_id = !empty($admin)? $admin->id : 0;
        $order_number = rand(1, 999);
        $order_number = str_pad($order_number, 8, '0', STR_PAD_LEFT);
        $prefix = ($type == 'inbound') ? 'IN' : 'OUT';
        $order_number = $prefix.$order_number;

        $order_items = [];
        $no_of_orders = rand(1, 3);
        for ($i=0; $i<$no_of_orders; $i++) {
            $rand_digit = rand(0, count($items)-1);
            $order_items[] = [
                'item' => $items[$rand_digit]['id'],
                'qty' => 5
            ];
        }

        $order_items = serialize($order_items);
        return [
            'order_number' => $order_number,
            'item' => $order_items,
            'type' => $type,
            'customer'=> $customer_id,
            'Remarks' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'updated_by' => $admin_id
        ];
    }
}
