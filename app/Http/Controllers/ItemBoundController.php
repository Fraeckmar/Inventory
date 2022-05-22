<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemBound;
use App\Models\User;
use App\Datatable\Datatable;
use App\Helpers\Order;
use App\Http\Controllers\Settings;
use Exception;
use Format;
use Helper;
use Field;
use App\Helpers\Receipt;

class ItemBoundController extends Controller
{

    private function update_order_number($order_number)
    {
        if (empty($order_number)) { return false; }
        $item = ItemBound::where('order_number', $order_number)->get('id')->first();
        if ($item) {
            $itemBound = ItemBound::findOrFail($item->id);
            $itemBound->order_number = $order_number;
            $itemBound->save();
            return $item->id;
        }
        return false;
    }

    public function index(Request $request)
    {
        $selectize_customers = [];
        $selectize_items = [];
        $item_where_clase = '';       
           
        if(empty($item_where_clase)) {
            $item_where_clase = "items.id IS NOT NULL";
        }    

        $items = Item::whereRaw($item_where_clase)->get()->toArray();
        if (!empty($items)) {
            $items = array_reduce($items, function($carry, $item){
                $carry[$item['id']] = $item;
                return $carry;
            });
            $selectize_items = array_reduce($items, function($carry, $item){
                $carry[$item['id']] = $item['item'];
                return $carry;
            });
        }
        
        $customers = User::where('role', 'customer')->get()->toArray();
        if (!empty($customers)) {
            $customers = array_reduce($customers, function($carry, $customer){
                $carry[$customer['id']] = $customer;
                return $carry;
            });
            $selectize_customers = array_reduce($customers, function($carry, $customer){
                $carry[$customer['id']] = $customer['name'];
                return $carry;
            });    
        }
        
        $selectize_customers = ['' => 'Customer'] + $selectize_customers;
        $selectize_items = ['' => 'Item'] + $selectize_items;

        // Filters
        $order_where_clause = "item_bounds.type = 'outbound'";
        if (!$request->filled('_search')) {
            if ($request->filled('type')) {
                $order_where_clause = "item_bounds.type = '{$request->type}'";
            }
            if ($request->filled('item')) {
                $order_where_clause .= " AND item_bounds.item LIKE '%\"{$request->item}\"%'";
            }
            if ($request->filled('customer')) {
                $order_where_clause .= " AND item_bounds.customer = '{$request->customer}'";
            }
            if ($request->filled('date_to') && $request->filled('date_from')) {
                $date_from = date('Y-m-d', strtotime($request->date_from));
                $date_to = date('Y-m-d', strtotime('+1 day', strtotime($request->date_to)));
                $order_where_clause .= " AND item_bounds.created_at BETWEEN '{$date_from}' AND '{$date_to}'";
            }
        }        
        if ($request->filled('_search')) {
            $order_where_clause .= " AND item_bounds.order_number LIKE '%{$request->_search}%'";
        }

        if (!Helper::auth_is_admin()) {
            $customer_auth = Auth::id();
            $order_where_clause = "item_bounds.customer = {$customer_auth}";
        }
        //DB::enableQueryLog();
        $itemBounds = ItemBound::whereRaw($order_where_clause)->paginate(25);
        $tbl_column_values = !empty($itemBounds) ? $itemBounds->toArray()['data'] : [];

        $tbl_column_values = array_reduce($tbl_column_values, function($carry, $order) use($customers, $items){
            $customer_id = $order['customer'];
            $order_items = unserialize($order['item']);
            $items_str = [];
            $unit_qty = 0;
            $unit_cost = 0;
            foreach ($order_items as $item) {
                $item_id = $item['item'];
                $item_qty = $item['qty'];
                $unit_qty += $item_qty;
                $item_price = array_key_exists($item_id, $items) ? $items[$item_id]['price'] : 0;
                $unit_cost += (float)$item_price * (int)$item_qty;
                $items_str[] = array_key_exists($item_id, $items) ? $items[$item_id]['item'] : '<span class="bg-red-400 text-white p-1 text-xs rounded">'.__('Deleted').': ID'.$item_id.'</span>';
            }
            $items_str = implode(', ', $items_str);
            $order['item'] = $items_str;
            $order['qty'] = $unit_qty;
            $order['unit_cost'] = Format::price($unit_cost);
            $order['order_number'] = '<a href="'.url('order').'/'.$order['id'].'" class="text-blue-600">'.$order['order_number'].'</a>';
            $order['customer'] = array_key_exists($customer_id, $customers) ? $customers[$customer_id]['name'] : $order['customer'];
            $order['created_at'] = Format::toDate($order['created_at']);
            $carry[] = $order;
            return $carry;
        });

        //$query = DB::getQueryLog();
        //echo $query[0]['query'];

        $tbl_column_fields = [
            [
                'heading' => __('Order #'),
                'key' => 'order_number',
                'td_class' => 'font-semibold text-sm w-32'
            ],
            [
                'heading' => __('Item'),
                'key' => 'item',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Customer'),
                'key' => 'customer',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Qty'),
                'key' => 'qty',
                'td_class' => 'text-sm w-24'
            ],
            [
                'heading' => __('Unit Cost'),
                'key' => 'unit_cost',
                'td_class' => 'text-sm w-24'
            ],
            [
                'heading' => __('Date'),
                'key' => 'created_at',
                'td_class' => 'text-sm w-36'
            ]
        ];

        $action_variables = [
            '{id}' => 'id',
            '{type}' => 'type'
        ];

        $tbl_actions = [
            [
                'action' => 'edit',
                'model' => 'order',
                'url' => 'order/{id}/edit?type={type}'
            ],
            [
                'action' => 'delete',
                'model' => 'order',
                'class' => 'delete-item',
                'extra' => 'data-label="Are you sure to delete this Item?" data-form="#delete-order{id}"'
            ],
            [
                'action' => 'receipt',
                'model' => 'order',
                'class' => 'order-receipt',
                'extra' => 'data-id={id}'
            ],
        ];

        $table_filters = [
            'date_from' => [
                'type' => 'date',
                'key' => 'date_from',
                'value' => '',
                'placeholder' => 'Date From',
                'class' => 'bg-gray-50 pl-10 py-2 lg:py-2 mb-1 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'wrap_class' => 'w-full md:w-1/3 lg:w-64'
            ],
            'date_to' => [
                'type' => 'date',
                'key' => 'date_to',
                'value' => '',
                'placeholder' => 'Date To',
                'class' => 'bg-gray-50 pl-10 py-2 lg:py-2 mb-1 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'wrap_class' => 'w-full md:w-1/3 lg:w-64'
            ],
        ];

        if (Helper::auth_is_admin()) {
            $table_filters['customer'] = [
                'type' => 'select',
                'key' => 'customer',
                'label' => __('Customer'),
                'value' => '',
                'options' => $selectize_customers,
                'class' => 'selectize px-4 py-3 lg:p-2 mb-1 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                'wrap_class' => 'w-full md:w-1/4 lg:w-36'
            ];
            $table_filters['item'] = [
                'type' => 'select',
                'key' => 'item',
                'label' => __('Items'),
                'value' => '',
                'options' => $selectize_items,
                'class' => 'selectize py-3 lg:p-2 mb-1 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                'wrap_class' => 'w-full md:w-1/4 lg:w-36'
            ];
            $table_filters['type'] = [
                'type' => 'select',
                'key' => 'type',
                'label' => __('Type'),
                'value' => '',
                'options' => ['outbound' => 'Outbound', 'inbound' => 'Inbound'],
                'class' => 'selectize py-3 lg:p-2 mb-1 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                'wrap_class' => 'w-full md:w-1/4 lg:w-36'
            ];
        }
        
        $dataTable = new Datatable('orders');
        $dataTable->set_table_column_fields($tbl_column_fields);
        $dataTable->set_table_column_values($tbl_column_values);        
        $dataTable->set_table_filters($table_filters);
        if (Helper::auth_is_admin()) {
            $dataTable->set_table_actions($tbl_actions);
            $dataTable->set_action_variables($action_variables);
        }        
        $dataTable->set_pagination_links($itemBounds->toArray());
        return view('order.list', ['dataTable' => $dataTable]);
    }

	// Item Inbound
    public function inbound()
	{
		$items = Item::all()->toArray();
        $itemIDS = array_column($items, 'id');
        $itemNames = array_column($items, 'item');
        $items = array_combine($itemIDS, $itemNames);
		return view('order.order', [
			'items' => $items,
            'type' => 'inbound',
		]);
	}

	// Item Outbound
	public function outbound()
	{
		$items = Item::all()->toArray();
        $itemIDS = array_column($items, 'id');
        $itemNames = array_column($items, 'item');
        $items = array_combine($itemIDS, $itemNames);

        $customers = User::where('role', 'customer')->get()->toArray();
        $customerIDS = array_column($customers, 'id');
        $customerNames = array_column($customers, 'name');
        $customers = array_combine($customerIDS, $customerNames);
        
		return view('order.order', [
			'items' => $items,
            'type' => 'outbound',
            'customers' => $customers
		]);
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = [
            'items' => 'required',
            'type' => 'required'
        ];
        if ($request->has('customer')) {
            $fields['customer'] = 'required';
        }
        $request->validate($fields);
        $type = $request->type;
        $remarks = $request->has('remarks')? $request->remarks : ''; 
        $order_number = Order::gen_number($type);
        $itemBoundSuccessMsg = 'Order <strong>'.$order_number.'</strong> created successfully!';

        if (!empty($request->items)) {
            // Update Item balance
            foreach ($request->items as $item) {
                $item_id = $item['item'];
                $item_qty = $item['qty'];
                $item = Item::find($item_id);            
                if($type == 'outbound'){
                    if($item->balance < $item_qty){
                        return back()->with('error', 'Error! The quantity must less than or equal to item current balance.');
                    }
                    $item->balance -= $item_qty; 
                }else{
                    $item->balance += $item_qty;
                }        
                $item->save();
            }

            // Save Order
            $itemBound = new ItemBound();
            $itemBound->order_number = $order_number;
            $itemBound->item = serialize($request->items);
            $itemBound->type = $request->type;
            $itemBound->customer = $request->customer;
            $itemBound->remarks = $remarks;
            $itemBound->updated_by = Auth::id();
            $itemBound->save();            
        }        

        // Return after successfully save
        return back()->with('success', $itemBoundSuccessMsg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = ItemBound::find($id)->toArray();
        $items = Item::all()->toArray();
        $items = array_reduce($items, function($carry, $item){
            $carry[$item['id']] = $item;
            return $carry;
        });
        
        $total_cost = 0;
        $item_data = unserialize($order['item']);
        if (!empty($item_data)) {
            foreach ($item_data as $item) {
                $item_id = $item['item'];
                $item_qty = $item['qty'];
                $item_price = array_key_exists($item_id, $items) ? $items[$item_id]['price'] : 0;
                $item_name = array_key_exists($item_id, $items) ? $items[$item_id]['item'] : 0;
                $unit_cost = $item_qty * $item_price;
                $total_cost += $unit_cost;
                $item_array = [
                    'item' => [
                        'label' => __('Item'),
                        'value' => $item_name
                    ],
                    'qty' => [
                        'label' => __('Qty'),
                        'value' => $item_qty
                    ],
                    'price' => [
                        'label' => __('Unit Price'),
                        'value' => $item_price
                    ],
                    'cost' => [
                        'label' => __('Unit Cost'),
                        'value' => $unit_cost
                    ]
                ];
                $order['items_data'][] = $item_array;
            }
        }

        $customer = User::where('id', $order['customer'])->get()->first();
        $updated_by = User::where('id', $order['updated_by'])->get()->first();
        $order['customer'] = !empty($customer) ? $customer->name : '';
        $order['created_at'] = Format::toDate($order['created_at']);
        $order['updated_by'] = !empty($updated_by) ? $updated_by->name : '';
        $order['total_cost'] = Format::price($total_cost);

        return view('order.show', [
            'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if(!Auth::check()){
            return redirect('login');
        }
        $items = Item::all()->toArray();
        $items = array_reduce($items, function($carry, $item){
            $carry[$item['id']] = $item['item'];
            return $carry;
        });

        $customers = User::where('role', 'customer')->get()->toArray();
        $customers = array_reduce($customers, function($carry, $customer){
            $carry[$customer['id']] = $customer['name'];
            return $carry;
        });
        $type = $request->filled('type') ? $request->type : 'outbound';
        $order = ItemBound::find($id)->toArray();
        if (!empty($order)) {
            $items_data = unserialize($order['item']);
            $items_list = [];
            foreach ($items_data as $item) {
                $item_id = $item['item'];
                $item_qty = $item['qty'];
                $item_name = array_key_exists($item_id, $items) ? $items[$item_id] : '';
                $items_list[] = [
                    'item' => $item_id,
                    'qty' => $item_qty
                ];
            }
        }
        $order['items'] = $items_list;
        return view('order.edit', [
            'order' => $order,
            'items' => $items,
            'customers' => $customers,
            'type' => $type
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error_messages = [];
        $type = 'outbound';
        $fields = [
            'items' => 'required',
            'customer' => 'required|numeric'
        ];
        if ($request->has('customer')) {
            $fields['customer'] = 'required';
        }
        $request->validate($fields);        
        $remarks = $request->has('remarks')? $request->remarks : '';
        $order_items = Order::get_items($id);
        $items = Order::get_items();
        $unique_items = [];
        foreach ($request->items as $order) {
            $request_id = $order['item'];
            $request_qty = $order['qty'];
            $item = Item::find($request_id);
            if ($request_qty <= 0) {
                $error_messages[] = "The qty. field must have value";
            }
            if (in_array($request_id, $unique_items)) {
                $error_messages[] = "Duplicate entry for '{$items[$request_id]['item']}'";
            }
            if ($item->balance < $request_qty) {
                $error_messages[] = "The remaining balance of '{$items[$request_id]['item']}' is less than your request.";
            }
            $unique_items[] = $request_id;
        }

        if (!empty($error_messages)) {
            return redirect("order/{$id}/edit")->with('errors_msg', $error_messages);    
        }

        foreach ($request->items as $order) {
            $request_id = $order['item'];
            $request_qty = $order['qty'];
            $item = Item::find($request_id);
            $prev_item_qty = array_key_exists($request_id, $order_items) ? $order_items[$request_id]['qty'] : 0;            
            $diff_balance = $prev_item_qty - $request_qty;  
            $new_item_bal = $item->balance + $diff_balance;
            $item->balance = $new_item_bal;
            $item->save();          
        }

        $itemBound = ItemBound::find($id);
        $itemBound = ItemBound::find($id);
        $itemBound->item = serialize($request->items);       
        $itemBound->type = $type;
        $itemBound->customer = $request->customer;
        $itemBound->remarks = $remarks;
        $itemBound->updated_by = Auth::id();
        $itemBound->save();
        return redirect('order/'.$id.'/edit')->with('success', 'Order update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = ItemBound::findOrFail($id);
        $order->delete();
        return redirect('order');
    }

    function report()
    {
        $type_options = Field::itemBoundTypes();
        $type_options = array_combine($type_options, $type_options);
        return view('items.report', [
            'customers' => User::where('role', 'customer')->get(),
            'type_options' => $type_options
        ]);
    }

    function generate_report(Request $request)
    {
        $where_clase = "id IS NOT NULL";
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to = date('Y-m-d', strtotime('+1 day', strtotime($request->date_to)));
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " item_bounds.created_at BETWEEN '{$date_from}' AND '{$date_to}'";
        }
        if ($request->has('customer') && !empty($request->customer)) {
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " item_bounds.customer = '{$request->customer}'";
        }

        if ($request->has('type') && !empty($request->type)) {
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " item_bounds.type = '".strtolower($request->type)."'";
        }
        $items = Order::get_items();
        $customers = User::where('role', 'customer')->get()->toArray();
        $customers = array_reduce($customers, function($carry, $customer){
            $carry[$customer['id']] = $customer['name'];
            return $carry;
        });

        $item_bounds = ItemBound::whereRaw($where_clase)->get()->toArray();   
        
        $csv_values = [];
        if (!empty($item_bounds)) {
            foreach ($item_bounds as $order) {
                $order_no = $order['order_number'];
                $customer_id = $order['customer'];
                $items_data = unserialize($order['item']);
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
                        $items_str[] = array_key_exists($item_id, $items) ? $items[$item_id]['item'].'-'.$item_qty : __('Deleted').': ID'.$item_id.'-'.$item_qty;
                    } 
                    $items_str = implode(', ', $items_str);
                    if (!array_key_exists($order_no, $csv_values)) {
                        $csv_values[$order_no]['qty'] = 0 ;
                        $csv_values[$order_no]['total_cost'] = 0;
                    }  
                    $csv_values[$order_no]['order_number'] = $order_no;
                    $csv_values[$order_no]['created_at'] = Format::toDate($order['created_at']); 
                    $csv_values[$order_no]['remarks'] = $order['remarks'];
                    $csv_values[$order_no]['customer'] = array_key_exists($customer_id, $customers) ? $customers[$customer_id] : ''; 
                    $csv_values[$order_no]['type'] = $order['type']; 
                    $csv_values[$order_no]['item'] = $items_str; 
                    $csv_values[$order_no]['qty'] += $unit_qty ;
                    $csv_values[$order_no]['total_cost'] += $unit_cost;
                }
            }
        }

        $fields = Field::boundFields('outbound');
        $fields = ['order_number' => ['label' => __('Order Number')]] + $fields;
        $fields['created_at'] = ['label' => __('Date')];
        $fields['qty'] = ['label' => __('Total Qty')];
        $fields['total_cost'] = ['label' => __('Total Cost')];
        

        $header = "";
        if (!empty($fields)) {
            foreach ($fields as $field_key => $field) {
                $header .= !empty($header) ? ',' : '';
                $header .= '"'.$field['label'].'"';
            }
        }
        $csv_content = $header."\r\n";
        if (!empty($csv_values)) {
            foreach ($csv_values as $order_number => $csv_value) {
                $csv_content_line = '';
                foreach ($fields as $item_key => $field) {                    
                    $value = $csv_value[$item_key];
                    $csv_content_line .= !empty($csv_content_line) ? ',' : '';
                    $csv_content_line .= '"'.$value.'"';
                }
                $csv_content .= $csv_content_line."\r\n";
            }
        }
        
        ob_get_clean();
        $fileName = 'Item-Orders-'.date('Ymdhis').'.csv';
        $filepath = storage_path('tmp').'/'.$fileName;
        $csv_file=fopen($filepath,"wb");
        fwrite($csv_file, $csv_content);
        fclose($csv_file);
        chmod($filepath, 0755);
        if (!empty($item_bounds)) {
            return Response::download($filepath, $fileName);
        }
        return redirect()->back()->with('error', __('No Result Found!'));
        
    }

    function generate_receipt($order_id) {
        return Receipt::print_order($order_id);
        die();
    }
}
