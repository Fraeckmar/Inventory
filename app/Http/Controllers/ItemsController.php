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

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
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
            ]
        ];
        $tbl_actions = [
            [
                'action' => '',
                'model' => 'items',
            ],
            [
                'action' => 'edit',
                'model' => 'items',
            ],
            [
                'action' => 'delete',
                'model' => 'items',
                'class' => 'delete-item',
                'extra' => 'data-label="Are you sure to delete this Item?"'
            ],
            // [
            //     'action' => 'receipt',
            //     'model' => 'items',
            //     'class' => 'item-receipt',
            // ],
        ];
        $tbl_column_values = Item::all()->toArray();
        $dataTable = new Datatable('items');
        $dataTable->set_table_column_fields($tbl_column_fields);
        $dataTable->set_table_column_values($tbl_column_values);
        $dataTable->set_table_actions($tbl_actions);
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
        $inbounds = DB::table('item_bounds')->leftJoin('users', 'item_bounds.updated_by', '=', 'users.id')
            ->select('item_bounds.*', 'users.name')
            ->where('item_bounds.type', '=', 'inbound')
            ->where('item_bounds.item', '=', $id)
            ->orderBy('item_bounds.created_at')
            ->get();

        $outbounds = DB::table('item_bounds')->leftJoin('users', 'item_bounds.updated_by', '=', 'users.id')
            ->select('item_bounds.*', 'users.name')
            ->where('item_bounds.type', '=', 'outbound')
            ->where('item_bounds.item', '=', $id)
            ->orderBy('item_bounds.created_at')
            ->get();

        $total_inbounds = $inbounds->reduce(function($carry, $item){
            return $carry + $item->qty;
        }, 0);

        $total_outbounds = $outbounds->reduce(function($carry, $item){
            return $carry + $item->qty;
        }, 0);

        return view('items.show',[
            'item' => Item::findOrFail($id),
            'inbounds' => $inbounds,            
            'outbounds' =>  $outbounds,
            'total_inbounds' => $total_inbounds,
            'total_outbounds' => $total_outbounds
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
