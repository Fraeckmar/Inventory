<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemBound;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use App\Datatable\Datatable;
use Field;
use App\Helpers\Helper;

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
        $order_where_clase = "item_bounds.type = 'outbound'";
        if ($request->filled('type')) {
            $order_where_clase = "item_bounds.type = '{$request->type}'";
        }
        if ($request->filled('_search')) {
            $order_where_clase .= " AND item_bounds.order_number = '{$request->_search}'";
        }
        if ($request->filled('item')) {
            $order_where_clase .= " AND item_bounds.item = '{$request->item}'";
        }
        if ($request->filled('customer')) {
            $order_where_clase .= " AND item_bounds.customer = '{$request->customer}'";
        }
        if ($request->filled('date_to') && $request->filled('date_from')) {
            $date_from = date('Y-m-d', strtotime('-1 day', strtotime($request->date_from)));
            $date_to = date('Y-m-d', strtotime($request->date_to));
            $order_where_clase .= " AND item_bounds.created_at BETWEEN '{$date_from}' AND '{$date_to}'";
        }

        DB::enableQueryLog();
        $tbl_column_values = ItemBound::whereRaw($order_where_clase)->get()->toArray();
        
        // $query = DB::getQueryLog();
        // echo $query[0]['query'];
        // die();
        $tbl_column_values = array_reduce($tbl_column_values, function($carry, $order) use($customers, $items){
            $customer_id = $order['customer'];
            $item_id = $order['item'];
            $order['created_at'] = date('y-m-d', strtotime($order['created_at']));
            $order['item'] = array_key_exists($item_id, $items) ? $items[$item_id]['item'] : $order['item'];
            $order['customer'] = array_key_exists($customer_id, $customers) ? $customers[$customer_id]['name'] : $order['customer'];
            $carry[] = $order;
            return $carry;
        });

        $tbl_column_fields = [
            [
                'heading' => __('Order #'),
                'key' => 'order_number',
                'td_class' => 'font-semibold text-sm'
            ],
            [
                'heading' => __('Item'),
                'key' => 'item',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Qty'),
                'key' => 'qty',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Customer'),
                'key' => 'customer',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Remarks'),
                'key' => 'remarks',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Date'),
                'key' => 'created_at',
                'td_class' => 'text-sm'
            ]
        ];

        $tbl_actions = [
            [
                'action' => 'edit',
                'model' => 'item-bound',
            ],
            [
                'action' => 'delete',
                'model' => 'item-bound',
                'class' => 'delete-item',
                'extra' => 'data-label="Are you sure to delete this Item?"'
            ],
            [
                'action' => 'receipt',
                'model' => 'item-bound',
                'class' => 'item-receipt',
            ],
        ];

        $table_filters = [
            [
                'type' => 'date',
                'key' => 'date',
                'value' => '',
                'placeholder_c1' => 'Date From',
                'placeholder_c2' => 'Date To',
                'class' => 'bg-gray-50 pl-10 py-2 lg:py-2 mb-1 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'wrap_class' => 'w-full md:w-1/3 lg:w-64'
            ],
            [
                'type' => 'select',
                'key' => 'customer',
                'label' => __('Customer'),
                'value' => '',
                'options' => $selectize_customers,
                'class' => 'selectize px-4 py-3 lg:p-2 mb-1 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                'wrap_class' => 'w-full md:w-1/4 lg:w-36'
            ],
            [
                'type' => 'select',
                'key' => 'item',
                'label' => __('Items'),
                'value' => '',
                'options' => $selectize_items,
                'class' => 'selectize py-3 lg:p-2 mb-1 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                'wrap_class' => 'w-full md:w-1/4 lg:w-36'
            ],
            [
                'type' => 'select',
                'key' => 'type',
                'label' => __('Type'),
                'value' => '',
                'options' => ['outbound' => 'Outbound', 'inbound' => 'Inbound'],
                'class' => 'selectize py-3 lg:p-2 mb-1 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                'wrap_class' => 'w-full md:w-1/4 lg:w-36'
            ],
        ];
        
        $dataTable = new Datatable('Order #');
        $dataTable->set_table_column_fields($tbl_column_fields);
        $dataTable->set_table_column_values($tbl_column_values);
        $dataTable->set_table_actions($tbl_actions);
        $dataTable->set_table_filters($table_filters);
        return view('order.list', ['dataTable' => $dataTable]);
    }

	// Item Inbound
    public function inbound()
	{
		$items = Item::all()->toArray();
        $itemIDS = array_column($items, 'id');
        $itemNames = array_column($items, 'item');
        $items = array_combine($itemIDS, $itemNames);
		return view('items.item-bound', [
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
        
		return view('items.item-bound', [
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
            'item' => 'required',
            'qty' => 'required|numeric',
            'type' => 'required'
        ];
        if ($request->has('customer')) {
            $fields['customer'] = 'required';
        }
        $request->validate($fields);
        $type = $request->type;
        $remarks = $request->has('remarks')? $request->remarks : '';
        $lastOrder = ItemBound::where('type', $type)->orderBy('id', 'DESC')->get('order_number')->first();
        $order_number = ($lastOrder) ? $int = (int) filter_var($lastOrder->order_number, FILTER_SANITIZE_NUMBER_INT) : 0;
        $order_number ++;
        $order_number = str_pad($order_number, 6, '0', STR_PAD_LEFT);
        $prefix = ($type == 'inbound') ? 'IN' : 'OUT';
        $order_number = $prefix.$order_number;
        // Save Inbound
        $itemBound = new ItemBound();
        $itemBound->order_number = $order_number;
        $itemBound->item = $request->item;     
        $itemBound->qty = $request->qty;
        $itemBound->type = $request->type;
        $itemBound->customer = $request->customer;
        $itemBound->remarks = $remarks;
        $itemBound->updated_by = Auth::id();
        $itemBound->save();

        // Update Item balance
        $item = Item::find($request->item);
        $itemBoundSuccessMsg = 'Order <strong>'.$order_number.'</strong> created successfully!';
        if($type == 'outbound'){
            if($item->balance < $request->qty){
                return back()->with('error', 'Error! The quantity must less than or equal to item current balance.');
            }
            $item->balance -= $request->qty; 
        }else{
            $item->balance += $request->qty;
        }        
        $item->save();

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

        return view('order.edit', [
            'itemBound' => ItemBound::find($id),
            'items' => $items,
            'customers' => $customers
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
        $type = 'outbound';
        $fields = [
            'item' => 'required',
            'qty' => 'required|numeric',
            'customer' => 'required|numeric'
        ];
        if ($request->has('customer')) {
            $fields['customer'] = 'required';
        }
        $request->validate($fields);        
        $remarks = $request->has('remarks')? $request->remarks : '';

        // Get  prev Outbound Qty
        
        

        // Save Inbound
        $itemBound = ItemBound::find($id);
        $outbound_prev_qty = $itemBound->qty;
        $itemBound->item = $request->item;        
        $itemBound->qty = $request->qty;
        $itemBound->type = $type;
        $itemBound->customer = $request->customer;
        $itemBound->remarks = $remarks;
        $itemBound->updated_by = Auth::id();
        $itemBound->save();

        // Update Item balance
        $item = Item::find($request->item);
        $new_balance = $outbound_prev_qty;
        if ($outbound_prev_qty < $request->qty) {
            $new_balance = $new_balance - ($request->qty - $outbound_prev_qty);
        } else {
            $new_balance = $new_balance + ($outbound_prev_qty - $request->qty);
        }
        $item->balance = $new_balance;
        $item->save();
        return redirect('item-bound/'.$id.'/edit')->with('success', 'Order update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
        $where_clase = "item_bounds.id IS NOT NULL";
        if ($request->has('date_from') && $request->has('date_to') && !empty($request->date_from) && !empty($request->date_to)) {
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to = date('Y-m-d', strtotime($request->date_to));
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " item_bounds.created_at >= '".$date_from."' AND item_bounds.created_at <= '".$date_to."'";
        }
        if ($request->has('customer') && !empty($request->customer)) {
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " `customer` = ".$request->customer;
        }

        if ($request->has('type') && !empty($request->type)) {
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " item_bounds.type = '".strtolower($request->type)."'";
        }

        $item_bounds = ItemBound::whereRaw($where_clase)->get()->toArray();        
        $fields = Field::boundFields('outbound');
        $fields['created_at'] = ['label' => __('Date')];
        $customers = User::where('role', 'customer')->get()->toArray();
        $customers = array_reduce($customers, function($carry, $customer){
            $carry[$customer['id']] = $customer['name'];
            return $carry;
        });

        $items = Item::all()->toArray();
        $items = array_reduce($items, function($carry, $item){
            $carry[$item['id']] = $item['item'];
            return $carry;
        });

        $header = "";
        if (!empty($fields)) {
            foreach ($fields as $field_key => $field) {
                $header .= !empty($header) ? ',' : '';
                $header .= '"'.$field['label'].'"';
            }
        }
        $data_options = [
            'customer' => $customers,
            'item' => $items
        ];
        $csv_content = $header."\r\n";

        if (!empty($item_bounds)) {
            foreach ($item_bounds as $item) {
                $csv_content_line = '';
                foreach ($fields as $item_key => $field) {                    
                    $value = $item[$item_key];
                    if (in_array($item_key, ['item', 'customer'])) {
                        if (array_key_exists($value, $data_options[$item_key])) {
                            $value = $data_options[$item_key][$value];                            
                        }                        
                    }
                    if ($item_key == 'created_at') {
                        $value = date('Y-m-d', strtotime($value));
                    }
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
}
