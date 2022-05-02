<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemBound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\Order;
use Helper;

class PageController extends Controller
{
	// Index
    public function index()
    {
    	return view('dashboard.index');
    }
    // Dashboard
    public function dashboard( Request $request )
    {
    	if(!Auth::check()){
    		return redirect('/login');
    	}
        if(Helper::auth_is_admin()) {
            $revenue = Order::get_order_summary();
            $orders = ItemBound::where('type', 'outbound')->orderBy("created_at")->get()->count();
            $customers = User::where('role', 'customer')->get()->count();
            $items  = Item::all()->count();
            return view('dashboard.dashboard', [
                'revenue' => $revenue,
                'customers' => $customers,
                'orders' => $orders
            ]);
        }
        return redirect('/order');
    }

    // Customers
    public function customers()
    {
        // $customers = DB::table('users')
        // ->leftJoin("item_bounds", "users.id", "=", "item_bounds.item")
        // ->selectRaw("
        //     users.*,
        //     (SELECT SUM(item_bounds.qty) FROM item_bounds WHERE item_bounds.customer = users.id AND item_bounds.type = 'outbound') AS total_items,
        //     (SELECT SUM(item_bounds.qty * items.price) as total FROM item_bounds INNER JOIN items ON item_bounds.item = items.id  WHERE item_bounds.customer = users.id AND item_bounds.type = 'outbound') AS total_amount
        // ")
        // ->where('users.role', 'customer')
        // ->distinct()
        // ->get()
        // ->toArray();
        // return view('customer.list', [
        //     'customers' => $customers
        // ]);
    }

    // registration
	public function register()
	{
        $admin_users = User::where('role', 'administrator')->get()->toArray();
		return view('dashboard.register', [
            'admin_users' => $admin_users
        ]);
	}

    // Login
    public function login()
    {
        if(Auth::check()){
            return redirect('dashboard');
        }
    	return view('dashboard.login');
    }
}
