<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Review;
use App\Product;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\Review as ReviewResource;

class GuestAPIController extends Controller
{

    public function review(Request $request) {

        $userIP = $_SERVER['REMOTE_ADDR'];

        $review = new Review;
        $review->product_id = $request->productId;
        $review->score = $request->rating;
        $review->comment = $request->comment;
        $review->ip = $userIP;
        
        $product = Product::findOrFail($request->productId);
        
        $userReviews = $product->reviews()->where('ip', $userIP)->get();

        if(count($userReviews)) {
            abort(403); // Duplicate record
        }

        if(!$product->reviews()->save($review)) {
            abort(404); // Failed operation
        }

        return new ReviewResource($review);
    }

}
