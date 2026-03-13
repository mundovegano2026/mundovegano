<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Resources\CAOPCollection as CAOPCollectionResource;
use App\Caop;
use App\User;

class CAOPAPIController extends Controller
{


    public function getDistritos(Request $request) {        
     
        $distritoList = Caop::groupBy('distrito')->orderBy('distrito')->get();
        

        return response()->json([
            'error' => false,
            'message' => 'Distritos obtidos.',
            'distritos' => new CAOPCollectionResource($distritoList)
        ]);
        
    }

    public function getConcelhos($distrito)
    {
        $concelhoList = Caop::where('distrito', $distrito)->groupBy('concelho')->orderBy('concelho')->get();
        

        return response()->json([
            'error' => false,
            'message' => 'Concelhos obtidos.',
            'concelhos' => new CAOPCollectionResource($concelhoList)
        ]);
    }

    public function getFreguesias($concelho)
    {
        $freguesiaList = Caop::where('concelho', $concelho)->groupBy('freguesia')->orderBy('freguesia')->get();
        

        return response()->json([
            'error' => false,
            'message' => 'Concelhos obtidos.',
            'freguesias' => new CAOPCollectionResource($freguesiaList)
        ]);
    }


    public function store(Request $request) {        
     
        $product = Product::with('stores')->find($request->product);
        $store = Store::where('name', $request->name)->first();
        if($product && $store) {
            $product->stores()->attach($store, [ 'price'=> $request->price ]);
            $product->save();
        }

        return response()->json([
            'error' => false,
            'message' => 'Loja registada.',
            'store' => new StoreResource($store)
        ]);
    }

    public function updatelocation(Request $request) {
        

        $user = User::findOrFail($request->user()->id);
        
        if (!$user) {
            return response([
                'message' => ['Utilizador inválido.']
            ], 404);
        }
    
        $response = [
            'user' => $user
        ];

        $distrito = Caop::where('distrito', $request->distrito)->first();
        if($distrito != null) {
            $concelho = Caop::where('concelho', $request->concelho)->first(); 
        }
        if($distrito == null || $concelho == null) {
            $user->distrito = '';
            $user->concelho = '';
        } else {
            $user->concelho = $concelho->concelho;
            if($user->concelho != '') {
                $user->distrito = $distrito->distrito;
            } else {
                $user->distrito = '';
            }
        }

        $user->save();
    
        return response($response, 201);

    }


}
