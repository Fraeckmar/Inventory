<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Settings;
use App\Datatable\Datatable;
use App\Models\Item;
use App\Models\ItemBound;
use App\Models\User;
use App\Helpers\Order;
use Helper;
use Field;
use Format;

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
        // dd(Order::get_inbound_graphs());
    	if(!Auth::check()){
    		return redirect('/login');
    	}
        if(Helper::auth_is_admin()) {
            $revenue = Order::get_order_summary();
            $orders = ItemBound::where('type', 'outbound')->orderBy("created_at")->get()->count();
            $customers = User::where('role', 'customer')->get()->count();
            $week_dates = Helper::getStartAndEndDate('m/d/Y');
            $critical_items = Order::getCriticalItems();

            return view('dashboard.dashboard', [
                'revenue' => $revenue,
                'customers' => $customers,
                'orders' => $orders,
                'week_dates' => $week_dates,
                'critical_items' => $critical_items
            ]);
        }
        return redirect('/orders');
    }

    // Customers
    public function customers(Request $request)
    {
        $where_clase = "users.role = 'customer'";
        if ($request->filled('date_to') && $request->filled('date_from')) {
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to = date('Y-m-d', strtotime('+1 day', strtotime($request->date_to)));
            $where_clase .= " AND users.created_at BETWEEN '{$date_from}' AND '{$date_to}'";
        }

        $users = User::whereRaw($where_clase)->paginate(25);
        $tbl_column_values = !empty($users) ? $users->toArray()['data'] : [];
        $tbl_column_values = array_reduce($tbl_column_values, function($carry, $user){
            $user['name'] = '<a href="'.url('users').'/'.$user['id'].'" class="text-blue-600">'.$user['name'].'</a>';
            $user['created_at'] = Format::toDate($user['created_at']);
            $carry[$user['id']] = $user;
            return $carry;
        });

        $items = Order::get_item_prices();
        $customer_orders = DB::table('users')
        ->leftJoin("item_bounds", "users.id", "=", "item_bounds.customer")
        ->selectRaw("users.*, item_bounds.item")
        ->where('users.role', 'customer')
        ->get()
        ->toArray();
        if (!empty($users) && !empty($customer_orders) && !empty($tbl_column_values)) {
            foreach ($customer_orders as $customer) {
                $items_data = unserialize($customer->item);
                $unit_cost = 0;
                $unit_qty = 0;
                if (!empty($items_data)) {
                    foreach ($items_data as $item) {
                        $item_id = $item['item'];
                        $item_qty = $item['qty'];
                        $unit_qty += $item_qty;
                        $item_price = array_key_exists($item_id, $items) ? $items[$item_id] : 0;
                        $unit_cost += $item_qty * $item_price;
                    } 
                    if (array_key_exists($customer->id, $tbl_column_values)) {
                        if (!array_key_exists('total_qty', $tbl_column_values[$customer->id])) {
                            $tbl_column_values[$customer->id]['total_qty'] = 0;
                            $tbl_column_values[$customer->id]['total_cost'] = 0;
                        }     
                        $tbl_column_values[$customer->id]['total_qty'] += $unit_qty;
                        $tbl_column_values[$customer->id]['total_cost'] += $unit_cost;
                    }
                }                
            }
        }

        $tbl_column_fields = [
            [
                'heading' => __('Customer'),
                'key' => 'name',
                'td_class' => 'font-semibold text-sm'
            ],
            [
                'heading' => __('Email'),
                'key' => 'email',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Address'),
                'key' => 'address',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Date'),
                'key' => 'created_at',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Total Qty'),
                'key' => 'total_qty',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Total Cost'),
                'key' => 'total_cost',
                'td_class' => 'text-sm'
            ]
        ];
        $tbl_actions = [
            [
                'action' => 'edit',
                'model' => 'users',
                'url' => 'users/{id}/edit'
            ],
            [
                'action' => 'delete',
                'model' => 'users',
                'class' => 'delete-item',
                'extra' => 'data-label="Are you sure to delete this Item?" data-form="#delete-users{id}"'
            ]
        ];

        $action_variables = [
            '{id}' => 'id'
        ];

        $table_filters = [
            'date_from' => [
                'type' => 'date',
                'key' => 'date_from',
                'value' => '',
                'placeholder' => 'Date From',
                'class' => 'bg-gray-50 pl-10 py-2 lg:py-2 mb-1 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'wrap_class' => 'w-full md:w-1/3 lg:w-80'
            ],
            'date_to' => [
                'type' => 'date',
                'key' => 'date_to',
                'value' => '',
                'placeholder' => 'Date To',
                'class' => 'bg-gray-50 pl-10 py-2 lg:py-2 mb-1 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'wrap_class' => 'w-full md:w-1/3 lg:w-80'
            ],
        ];
        
        $dataTable = new Datatable('users');
        $dataTable->set_table_column_fields($tbl_column_fields);
        $dataTable->set_table_column_values($tbl_column_values);
        $dataTable->set_action_variables($action_variables);
        $dataTable->set_table_actions($tbl_actions);
        $dataTable->set_table_filters($table_filters);
        $dataTable->search_placeholder = 'Search Customer..';
        $dataTable->set_pagination_links($users->toArray());
        return view('customer.list', ['dataTable' => $dataTable]);
    }

    // registration
	public function register()
	{
        $admin_users = User::where('role', 'administrator')->get()->toArray();
        $roles = Field::customerRoles();
        if (!Auth::check() && !empty($admin_users)) {
            unset($roles['administrator']);
        }
		return view('dashboard.register', [
            'admin_users' => $admin_users,
            'roles' => $roles
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
