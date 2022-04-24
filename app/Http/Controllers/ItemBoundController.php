<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemBound;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Field;

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
        $where_clase = "`id` IS NOT NULL";
        if ($request->has('date_from') && $request->has('date_to') && !empty($request->date_from) && !empty($request->date_to)) {
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to = date('Y-m-d', strtotime($request->date_to));
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " `created_at` >= '".$date_from."' AND `created_at` <= '".$date_to."'";
        }
        if ($request->has('customer') && !empty($request->customer)) {
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " `customer` = ".$request->customer;
        }

        if ($request->has('type') && !empty($request->type)) {
            $where_clase .= !empty($where_clase) ? " AND" : "";
            $where_clase .= " `type` = '".strtolower($request->type)."'";
        }

        $item_bounds = ItemBound::whereRaw($where_clase)->get()->toArray();        
        $fields = Field::boundFields('outbound');
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
