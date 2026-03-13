<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product_report;
use App\Report_type;
use App\Status;
use App\User;

class Product_reportsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data["breadcrumbs"] = ["Validação", "Denúncias"];
        $data["product_reports"] = Product_report::where('status_id', 1)
                                    ->orderBy('id', 'asc')
                                    ->paginate(10);

        foreach($data["product_reports"] as $product_report) {
            $product_report->type = Report_type::find($product_report->report_type_id);
        }

        return view('admin.product_reports.index')->with('data', $data);
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

        $productReport = Product_report::find($request->id);
        if($productReport != null) {
            $productReport->status_id = $request->status;
            $productReport->save();
            $status = "success";
            $message = "Denúncia Atualizada";
        } else 
        {
            $message = "Registo não existente";
            $status = "error";
        }


        $returnLink = '/admin/product_reports/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/product_reports';

        return redirect($returnLink)->with($status, $message);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_report';
   
        $data["product_report"] = Product_report::find($id);
        $data["product_report"]->type = Report_type::find($data["product_report"]->report_type_id);  
        $data["product_report"]->user = User::find($data["product_report"]->user_id);  

        // $data["product_report"]->product = Product_report::find($data["price"]->product_id);  
        // $data["currentPrice"] = DB::table('product_stores')
        //                             ->where('product_id', $data["price"]->product_id)
        //                             ->where('store_id', $data["price"]->store_id)
        //                             ->where('status_id', 2)
        //                             ->orderBy('id', 'desc')->first();   
       
        $data["statuses"] = Status::all();

        return view($modalName)->with('data', $data);
    }

}
