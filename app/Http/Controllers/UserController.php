<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ItemBound;
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
    		'role' => 'required',
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
        $orders = DB::table('item_bounds')
        ->leftJoin("items", "item_bounds.item", "=", "items.id")
        ->leftJoin("users", "item_bounds.customer", "=", 'users.id')
        ->selectRaw("item_bounds.qty, item_bounds.created_at as date, items.item, items.price, item_bounds.qty * items.price as item_total")
        ->whereRaw("item_bounds.item = items.id AND item_bounds.customer = ?", [$id])
        ->orderBy("date")
        ->get();

        $total_order = $orders->reduce(function($carry, $item){
            return $carry + $item->item_total;
        }, 0);
        return view('customer.dashboard', [
            'customer' => User::find($id),
            'orders' => $orders,
            'total_order' => $total_order
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
        return redirect('user/'.$id.'/edit')->with('message', 'Customer update successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('customers');
    }
}
