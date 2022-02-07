<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

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
        //DB::enableQueryLog();
        if(in_array(Auth::user()->role, ['administrator', 'staff'])){
            $revenue = DB::table('item_bounds')
            ->leftJoin('items', 'item_bounds.item', '=', 'items.id')
            ->select(DB::raw("
                SUM(items.price*item_bounds.qty) AS total,
                (SELECT SUM(m_items.price*m_bounds.qty) FROM item_bounds AS m_bounds INNER JOIN items AS m_items on m_bounds.item = m_items.id WHERE extract(month from m_bounds.created_at) = '".date('m')."' AND m_bounds.type='outbound') AS monthly,
                (SELECT SUM(w_items.price*w_bounds.qty) FROM item_bounds AS w_bounds INNER JOIN items AS w_items on w_bounds.item = w_items.id WHERE extract(week from w_bounds.created_at) = '".abs(date('W'))."' AND w_bounds.type='outbound') AS weekly,
                (SELECT SUM(d_items.price*d_bounds.qty) FROM item_bounds AS d_bounds INNER JOIN items AS d_items on d_bounds.item = d_items.id WHERE CAST(d_bounds.created_at AS DATE) = CAST('".date('Y-m-d')."' AS DATE) AND d_bounds.type='outbound') AS daily
            "))
            ->whereRaw("item_bounds.type = 'outbound'")
            ->first();
            //$query = DB::getQueryLog();
            //echo $query[0]['query'];
            //dd($revenue);
            $customers = User::where('role', 'customer')->get()->count();
            $items  = Item::all()->count();
            return view('dashboard.dashboard', [
                'revenue' => $revenue,
                'customers' => $customers,
                'items' => $items
            ]);
        }

        $userId = Auth::user()->id;
        $orders = DB::table('item_bounds')
            ->leftJoin("items", "item_bounds.item", "=", "items.id")
            ->leftJoin("users", "item_bounds.customer", "=", 'users.id')
            ->selectRaw("item_bounds.qty, item_bounds.created_at as date, items.item, items.price, item_bounds.qty * items.price as item_total")
            ->whereRaw("item_bounds.item = items.id AND item_bounds.customer = ?", [$userId])
            ->orderBy("date")
            ->get();

        $total_order = $orders->reduce(function($carry, $item){
            return $carry + $item->item_total;
        }, 0);
        
        return view('customer.dashboard', [
            'customer' => Auth::user(),
            'orders' => $orders,
            'total_order' => $total_order
        ]);
    }

    // Reports
    public function report()
    {
        return view('items.report', [
            'customers' => User::where('role', 'customer')->get(),
        ]);
    }

    // Customers
    public function customers()
    {
        $customers = DB::table('users')
        ->leftJoin("item_bounds", "users.id", "=", "item_bounds.item")
        ->selectRaw("
            users.*,
            (SELECT SUM(item_bounds.qty) FROM item_bounds WHERE item_bounds.customer = users.id AND item_bounds.type = 'outbound') AS total_items,
            (SELECT SUM(item_bounds.qty * items.price) as total FROM item_bounds INNER JOIN items ON item_bounds.item = items.id  WHERE item_bounds.customer = users.id AND item_bounds.type = 'outbound') AS total_amount
        ")
        ->where('users.role', 'customer')
        ->get()
        ->toArray();
        return view('customer.list', [
            'customers' => $customers
        ]);
    }

    // registration
	public function register()
	{
		return view('dashboard.register');
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
