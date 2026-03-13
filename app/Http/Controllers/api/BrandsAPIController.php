<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Resources\BrandCollection as BrandCollectionResource;
use App\Http\Resources\Brand as BrandResource;
use DB;
use App\Brand;
use App\Product;

class BrandsAPIController extends Controller
{


    public function fetch(Request $request) {        
     
        $query = $request->name;
        $data = DB::table('brands')
        ->where('name', 'LIKE', "%{$query}%")
        ->take(10)
        ->get();
        
        return new BrandCollectionResource($data);
        
    }

    public function fetchname(Request $request) {        
        return 
        $brandList = DB::table('brands')
            ->where('name', 'LIKE', '%' . $request->name . '%')
            ->orderBy('name', 'ASC')
            ->take(10)
            ->get();
        
        return new BrandCollectionResource($brandList);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function brandByName($name)
    {
        // Get article
        $brand = Brand::with([
            'products' => function($query) {
                $query->take(10);
            }
        ])->where('name', $name)->take(1)->first();

        // Return single article as a resource
        return new BrandResource($brand);

    }


}
