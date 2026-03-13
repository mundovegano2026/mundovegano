<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use View;
use App\Stat;
use App\Product;
use App\Brand;
use App\Store;
use App\Product_report;
use DB;


class AdminController extends Controller
{

    private function isMobileDevice(){
        $aMobileUA = array(
            '/iphone/i' => 'iPhone', 
            '/ipod/i' => 'iPod', 
            '/ipad/i' => 'iPad', 
            '/android/i' => 'Android', 
            '/blackberry/i' => 'BlackBerry', 
            '/webos/i' => 'Mobile'
        );
    
        //Return true if Mobile User Agent is detected
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
                return true;
            }
        }
        //Otherwise return false..  
        return false;
    }

    private function registerVisit() {

        $stat = new Stat;
        $stat->is_mobile = $this->isMobileDevice();
        $stat->ip = $_SERVER['REMOTE_ADDR'];
        $stat->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $stat->user_id = Auth::user()->id;
        $stat->save();

    }

    public function index() {
        $this->registerVisit();
        $data = array();
        $data["breadcrumbs"] = ["Início", "Painel Principal"];
        
        // Get total visits
        $data["totalVisits"] = Stat::count();
        // Get total visits
        $data["totalAuthVisits"] = Stat::where('user_id', '!=', 0)->count();
        // Get visitor stats for 10 days
        $date = \Carbon\Carbon::today()->subDays(6);
        $data["visits"] = Stat::where('created_at','>=',$date)
            ->get()    
            ->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('d/m/Y'); // grouping by years
            });

        $data["totalProducts"] = 10;


        return view('admin.dashboard')->with("data", $data);
    }

    public function tasks() {

        $data = array();
        $data["breadcrumbs"] = ["Início", "Tarefas"];

        // Products Validation
        $data["productsToValidate"] = Product::where('status_id', 1)->count();
        $data["totalProducts"] = Product::count();
        $data["percentageProducts"] = $data["totalProducts"] == 0 ? 100 : (100 - ceil($data["productsToValidate"] / $data["totalProducts"] * 100));
        //if($data["percentageProducts"] == 100 && $data["productsToValidate"] != $data["totalProducts"]) $data["percentageProducts"] = 99;

        // Stores Validation
        $data["storesToValidate"] = Store::where('status_id', 1)->count();
        $data["totalStores"] = Store::count();
        $data["percentageStores"] = $data["totalStores"] == 0 ? 100 : (100 - ceil($data["storesToValidate"] / $data["totalStores"] * 100));
        //if($data["percentageStores"] == 100 && $data["storesToValidate"] != $data["totalStores"]) $data["percentageStores"] = 99;

        // Brands Validation
        $data["brandsToValidate"] = Brand::where('status_id', 1)->count();
        $data["totalBrands"] = Brand::count();
        $data["percentageBrands"] = $data["totalBrands"] == 0 ? 100 : (100 - ceil($data["brandsToValidate"] / $data["totalBrands"] * 100));
        //if($data["percentageBrands"] == 100 && $data["brandsToValidate"] != $data["totalBrands"]) $data["percentageBrands"] = 99;

        // Product Reports Validation
        $data["productReportsToValidate"] = Product_report::where('status_id', 1)->count();
        $data["totalProductReports"] = Product_report::count();
        $data["percentageProductReports"] = $data["totalProductReports"] == 0 ? 100 : (100 - ceil($data["productReportsToValidate"] / $data["totalProductReports"] * 100));
        //if($data["percentageProductReports"] == 100 && $data["productReportsToValidate"] != $data["totalProductReports"]) $data["percentageProductReports"] = 99;

        // Product Reports Validation
        $data["pricesToValidate"] = DB::table('product_stores')
                                        ->where('status_id', 1)
                                        ->count();
        $data["totalPrices"] = DB::table('product_stores')
                                        ->count();
        $data["percentagePrices"] = $data["totalPrices"] == 0 ? 100 : (100 - ceil($data["pricesToValidate"] / $data["totalPrices"] * 100));
        //if($data["percentageProductReports"] == 100 && $data["productReportsToValidate"] != $data["totalProductReports"]) $data["percentageProductReports"] = 99;

        return view('admin.tasks')->with("data", $data);
    }

    public function login() {
        
        $data = array();
        $data["breadcrumbs"] = ["Início", "Login"];
        return view('admin.auth.login')->with("data", $data);
    }

    public function logout() {
        
        auth()->logout();
        $data = array();
        $data["breadcrumbs"] = ["Home", "Login"];
        return view('admin.auth.login')->with("data", $data);
    }
}