<?php

namespace App\Http\Controllers;

use App\Helpers\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ItemBound;
use App\Models\User;
use Format;

class UserController extends Controller
{

    public function index()
    {

    }
    public function create()
    {

    }
	// Save user
    public function store(Request $request)
    {
    	$request->validate([
    		'name' => 'required|string',
    		'email' => 'required|unique:users',
    		'role' => 'required:string',
    		'password' => 'required',
    	]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('login');
    }
    public function show($id)
    {
        $customer = User::find($id);
        $items = Order::get_items();
        $customer_orders = DB::table('users')
        ->leftJoin("item_bounds", "users.id", "=", "item_bounds.customer")
        ->selectRaw("users.*, item_bounds.id as order_id, item_bounds.item, item_bounds.order_number, item_bounds.created_at, item_bounds.remarks, item_bounds.updated_by")
        ->where('users.role', 'customer')
        ->where('users.id', $id)
        ->get()
        ->toArray();

        $orders = [];
        if (!empty($customer_orders) && !empty($items)) {
            foreach ($customer_orders as $order) {
                $updated_by = User::where('id', $order->updated_by)->get()->first();
                $order_no = $order->order_number;
                $items_data = unserialize($order->item);
                $unit_cost = 0;
                $unit_qty = 0;
                $items_str = [];
                if (!empty($items_data)) {
                    foreach ($items_data as $item) {
                        $item_id = $item['item'];
                        $item_qty = $item['qty'];
                        $unit_qty += $item_qty;
                        $item_price = array_key_exists($item_id, $items) ? $items[$item_id]['price'] : 0;
                        $unit_cost += $item_qty * $item_price;
                        $items_str[] = array_key_exists($item_id, $items) ? $items[$item_id]['item'].'-'.$item_qty : '<span class="bg-red-400 text-white p-1 text-xs rounded">'.__('Deleted').': ID'.$item_id.'-'.$item_qty.'</span>';
                    } 
                    $items_str = implode('<br>', $items_str);
                    if (!array_key_exists($order_no, $orders)) {
                        $orders[$order_no]['total_cost'] = 0;
                    }
                    $orders[$order_no]['unit_qty'] = $unit_qty;                    
                    $orders[$order_no]['unit_cost'] = $unit_cost;
                    $orders[$order_no]['order_number'] = "<a href='".url('order')."/{$order->order_id}'>{$order->order_number}</a>";   
                    $orders[$order_no]['items'] = $items_str;
                    $orders[$order_no]['created_at'] = Format::toDate($order->created_at);
                    $orders[$order_no]['remarks'] = $order->remarks;
                    $orders[$order_no]['updated_by'] = $updated_by->name;
                    $orders[$order_no]['total_cost'] += $unit_cost;
                }                
            }
        }

        return view('customer.dashboard', [
            'customer' => $customer,
            'orders' => $orders
        ]);
    }

    public function edit($id)
    {
        return view('customer.edit', [
            'customer' => User::find($id)
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'role' => 'required'
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->role = $request->role;
        $user->save();
        return redirect('users/'.$id.'/edit')->with('message', 'Customer update successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('customers');
    }
}
