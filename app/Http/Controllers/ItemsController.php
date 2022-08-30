<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Settings;
use App\Models\Item;
use App\Models\User;
use App\Models\ItemBound;
use App\Datatable\Datatable;
use Format;
use Helper;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $where_clause = "items.id IS NOT NULL";
        if (!$request->filled('_search')) {
            if ($request->filled('category')) {
                $where_clause .= " AND items.category = '{$request->category}'";
            }
            if ($request->filled('date_to') && $request->filled('date_from')) {
                $date_from = date('Y-m-d', strtotime($request->date_from));
                $date_to = date('Y-m-d', strtotime('+1 day', strtotime($request->date_to)));
                $where_clause .= " AND items.created_at BETWEEN '{$date_from}' AND '{$date_to}'";
            }
        }        
        if ($request->filled('_search')) {
            $where_clause .= " AND items.item LIKE '%{$request->_search}%'";
        }

        $items = Item::whereRaw($where_clause)->paginate(25);
        $tbl_column_values = !empty($items) ? $items->toArray()['data'] : [];
        $tbl_column_values = array_reduce($tbl_column_values, function($carry, $item){
            $item['item'] = '<a href="'.url('items').'/'.$item['id'].'" class="text-blue-600">'.$item['item'].'</a>';
            $item['created_at'] = Format::toDate($item['created_at']);
            $carry[] = $item;
            return $carry;
        });

        $items_category = Settings::get('items_category');
        $items_category = array_reduce($items_category, function($carry, $category){
            $carry[strtolower($category)] = $category;
            return $carry;
        });
        $items_category = ['' => 'Category'] + $items_category;

        $tbl_column_fields = [
            [
                'heading' => __('Item'),
                'key' => 'item',
                'td_class' => 'font-semibold text-sm'
            ],
            [
                'heading' => __('Description'),
                'key' => 'description',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Price'),
                'key' => 'price',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Remaining Balance'),
                'key' => 'balance',
                'td_class' => 'text-sm'
            ],
            [
                'heading' => __('Category'),
                'key' => 'category',
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
                'model' => 'items',
                'url' => 'items/{id}/edit'
            ],
            [
                'action' => 'delete',
                'model' => 'order',
                'class' => 'delete-item',
                'extra' => 'data-label="Are you sure to delete this Item?" data-form="#delete-items{id}"'
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
                'wrap_class' => 'w-full md:w-1/3 lg:w-72'
            ],
            'date_to' => [
                'type' => 'date',
                'key' => 'date_to',
                'value' => '',
                'placeholder' => 'Date To',
                'class' => 'bg-gray-50 pl-10 py-2 lg:py-2 mb-1 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'wrap_class' => 'w-full md:w-1/3 lg:w-72'
            ],
            'category' => [
                'type' => 'select',
                'key' => 'category',
                'label' => __('Category'),
                'value' => '',
                'options' => $items_category,
                'class' => 'selectize px-4 py-3 lg:p-2 mb-1 w-full text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                'wrap_class' => 'w-full md:w-1/4 lg:w-36'
            ]
        ];
        
        $dataTable = new Datatable('items');
        $dataTable->set_table_column_fields($tbl_column_fields);
        $dataTable->set_table_column_values($tbl_column_values);
        $dataTable->set_action_variables($action_variables);
        $dataTable->set_table_actions($tbl_actions);
        $dataTable->set_table_filters($table_filters);
        $dataTable->set_pagination_links($items->toArray());
        return view('items.items', ['dataTable' => $dataTable]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::check()){
            return redirect('login');
        }
        $categories = !empty(Settings::get('items_category'))? array_map('trim', Settings::get('items_category')) : [];
        $categories = !empty($categories) ? array_combine($categories, $categories) : [];
        return view('items.add')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'item' => 'required',
            'description' => 'required',
            'price' => 'required:numeric',
            'balance' => 'required|numeric',
            'category' => 'required',
        ]);
        $item = new Item();
        $item->item = $request->item;
        $item->description  = $request->description;
        $item->price        = $request->price;
        $item->balance      = $request->balance;
        $item->category     = $request->category;
        $item->save();
        return back()->with('message', 'Item added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('items.show',[
            'item' => Item::findOrFail($id)
        ]);
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
        $setting = new Settings();
        $categories = array_map('trim', $setting->get('items_category'));
        $categories = !empty($categories) ? array_combine($categories, $categories) : [];
        return view('items.edit', [
            'item' => Item::find($id),
            'categories' => $categories
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
        $validate = $request->validate([
            'item' => 'required',
            'description' => 'required',
            'price' => 'required:numeric',
            'balance' => 'required|numeric',
            'category' => 'required',
        ]);
        
        $item = Item::find($id);
        $item->item = $request->item;
        $item->description  = $request->description;
        $item->price        = $request->price;
        $item->balance      = $request->balance;
        $item->category     = $request->category;
        $item->save();
        return redirect('items/'.$id.'/edit')->with('message', 'Item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return redirect('items');
    }
}
