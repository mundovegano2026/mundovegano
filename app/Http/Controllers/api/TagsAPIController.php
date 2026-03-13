<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Tag;
use App\Http\Resources\TagCollection as TagCollectionResource;
use App\Http\Resources\Tag as TagResource;

class TagsAPIController extends Controller
{


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        // Get tags
        $tagList = Tag::all();
     
        return new TagCollectionResource($tagList);

    }

    public function show($id)
    {
        // Get article
        // $category = Category::with('products')->findOrFail($id);

        // $subCategories = new CategoryCollectionResource(Category::with('products')->where('parent_id', $id)->orderBy('name')->get());
        
        // $category->subCategories = $subCategories;
        // // Return single article as a resource
        // return new CategoryResource($category);


        $tag = Tag::with([
            'products' => function($query) {
                $query->take(10);
            }
        ])->findOrFail($id);
        $tagId = $tag->id;
        // $productList = Product::whereExists(function($query) use($tagId)
        //     {
        //         $query->select(DB::raw(1))
        //             ->from('categorytrees')
        //             ->whereRaw('categorytrees.category_child = products.category_id AND categorytrees.category_parent = ' . $categoryId);
        //     }
        // )->take(100)->get();

      

        // Return single article as a resource
        return new TagResource($tag);

    }

}
