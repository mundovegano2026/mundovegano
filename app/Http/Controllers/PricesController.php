<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Product;
use App\Store;
use App\Status;

class PricesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data["breadcrumbs"] = ["Validação", "Preços"];
        $data["prices"] = DB::table('product_stores')
            ->where('status_id', 1)
            ->orderBy('id', 'asc')
            ->paginate(10);

        foreach($data["prices"] as $price) {
            $price->product = Product::find($price->product_id);
            $price->store = Store::find($price->store_id);
        }
        
        return view('admin.prices.index')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'status' => 'required'
        ]);

        $price = DB::table('product_stores')->find($request->id);
        if($price != null) {
            DB::table('product_stores')
                ->where('id', $request->id)
                ->update(['status_id' =>DB::raw($request->status)]);
            $status = "success";
            $message = "Preço Atualizado";
        } else 
        {
            $message = "Registo não existente";
            $status = "error";
        }


        $returnLink = '/admin/prices/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/prices';

        return redirect($returnLink)->with($status, $message);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_price';
   
        $data["price"] = DB::table('product_stores')->find($id);  

        $data["price"]->product = Product::find($data["price"]->product_id);  
        $data["currentPrice"] = DB::table('product_stores')
                                    ->where('product_id', $data["price"]->product_id)
                                    ->where('store_id', $data["price"]->store_id)
                                    ->where('status_id', 2)
                                    ->orderBy('id', 'desc')->first();   
       
        $data["statuses"] = Status::all();

        return view($modalName)->with('data', $data);
    }

}
