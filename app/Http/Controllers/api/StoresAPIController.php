<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Resources\StoreCollection as StoreCollectionResource;
use App\Http\Resources\ChainStoreCollection as ChainStoreCollectionResource;
use App\Http\Resources\Store as StoreResource;
use App\Http\Resources\Product as ProductResource;
use DB;
use App\User;
use App\Store;
use App\Chain;
use App\View_brand_store;
use App\Caop;
use App\Product;

class StoresAPIController extends Controller
{

    
    public function fetch(Request $request) {        
     
        $query = $request->name;
        $data = Store::with(['caop'])
        ->where('name', 'LIKE', "%{$query}%")
        ->where('status_id', 2)
        ->take(10)
        ->get();
        
        return new StoreCollectionResource($data);
        
    }

    public function geofetch(Request $request) {        
     
        $query = $request->name;
        $data = Store::with(['caop'])
        ->where('name', 'LIKE', "%{$query}%")
        ->where('status_id', 2)
        ->take(10)
        ->get();
        
        return new StoreCollectionResource($data);
        
    }

    public function fetchname($name) {        
     
        $data = View_brand_store::where('name', 'LIKE', "%{$name}%")
        ->take(10)
        ->get();
        
        return new ChainStoreCollectionResource($data);
        
    }


    public function store(Request $request) {        
     
        $product = Product::with('stores')->find($request->product);
        $store = Store::where('name', $request->name)->first();
        $chain = null;

        if($product != null) {
            
            if($store == null) {

                $caop = Caop::where('freguesia', $request->freguesia)->first();
                $store = new Store;
                $store->name = $request->name;
                $store->caop_id = $caop->id;
                $store->address = $request->address;
                
                // Get user
                if($request->user()!=null) {
                    $user = User::find($request->user()->id);        
                    $store->user = $user;
                }
                
                if($request->input('location') != '') {
                    $coordinates = explode(',', $request->input('location'));
                    $store->location = DB::raw("(ST_PointFromText('POINT(" . $coordinates[0] . " " . $coordinates[1] . ")'))");
                } else {
                    $store->location = null;
                }                
                
                $store->save();
            } 
            else {
          
                // Check for Chain
                $chain = Chain::find($store->chain_id);

            }

            if($product->stores->where('name', $request->name)->count()) {
                return response([
                    'error' => true,
                    'message' => ['Já existe uma loja com este nome associada ao produto.']
                ], 451);
            }

            if($chain != null) {
                $product->chains()->attach($chain, [ 'price'=> $request->price == "" ? 0 : $request->price ]);
            } else {
                $product->stores()->attach($store, [ 'price'=> $request->price == "" ? 0 : $request->price ]);
            }

            $product->save();
        }

        return response()->json([
            'error' => false,
            'message' => 'Loja registada.',
            'store' => new StoreResource($store)
        ]);
    }


    public function storeNew(Request $request) {        
     
        $store = Store::where('name', $request->name)->first();
            
        if($store == null) {

            $caop = Caop::where('freguesia', $request->freguesia)->first();
            $store = new Store;
            $store->name = $request->name;
            $store->caop_id = $caop->id;
            $store->save();

        } else {

            return response([
                'error' => true,
                'message' => ['Já existe uma loja com este nome.']
            ], 451);
            
        }

        return response()->json([
            'error' => false,
            'message' => 'Loja registada.',
            'store' => new StoreResource($store)
        ]);
    }


    public function storeNewLocation(Request $request) {        
     
        $store = Store::where('name', $request->name)->first();
            
        if($store == null) {

            $caop = Caop::where('freguesia', $request->freguesia)->first();
            if($caop == null) 
                $caop = Caop::where('dicofre', $request->freguesia)->first(); 
            $store = new Store;
            $store->name = $request->name;
            $store->caop_id = $caop->id;                
            $store->address = $request->address;
                
            // Get user
            if($request->user()!=null) {
                $user = User::find($request->user()->id);        
                $store->user = $user;
            }
            
            if($request->input('location') != '') {
                $coordinates = explode(',', $request->input('location'));
                $store->location = DB::raw("(ST_PointFromText('POINT(" . $coordinates[0] . " " . $coordinates[1] . ")'))");
            } else {
                $store->location = null;
            }   

            $store->save();

        } else {

            return response([
                'error' => true,
                'message' => ['Já existe uma loja com este nome.']
            ], 451);
            
        }

        return response()->json([
            'error' => false,
            'message' => 'Loja registada.',
            'store' => new StoreResource($store)
        ]);
    }


}
