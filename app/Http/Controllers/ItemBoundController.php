<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemBound;
use App\Models\User;

class ItemBoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

	// Item Inbound
    public function inbound()
	{
		$items = Item::all();
		return view('items.item-bound', [
			'items' => $items,
            'type' => 'inbound',
		]);
	}

	// Item Outbound
	public function outbound()
	{
		$items = Item::all();
		return view('items.item-bound', [
			'items' => $items,
            'type' => 'outbound',
            'customers' => User::where('role', 'customer')->get()
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
        $request->validate([
            'item' => 'required',
            'qty' => 'required|numeric',
            'type' => 'required'
        ]);
        $type = $request->type;
        $remarks = $request->has('remarks')? $request->remarks : '';
        // Save Inbound
        $itemBound = new ItemBound();
        $itemBound->item = $request->item;        
        $itemBound->qty = $request->qty;
        $itemBound->type = $request->type;
        $itemBound->customer = $request->customer;
        $itemBound->remarks = $remarks;
        $itemBound->updated_by = Auth::id();
        $itemBound->save();

        // Update Item balance
        $item = Item::find($request->item);
        $itemBoundSuccessMsg = __('Inbound successfully!');
        if($type == 'outbound'){
            if($item->balance < $request->qty){
                return back()->with('error', 'Error! The quantity input should not more than in current balance.');
            }
            $item->balance -= $request->qty;
            $itemBoundSuccessMsg = __('Outbound successfully!');
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
        //
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
}
