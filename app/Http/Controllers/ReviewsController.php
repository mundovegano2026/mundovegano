<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;
use App\Status;
use DB;

class ReviewsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = array();
        $data["breadcrumbs"] = ["Início", "Avaliações"];
        $data["search"] = "";
        // New product query
        $queryReview = Review::query();

        //Add sorting
        $queryReview->orderBy('id','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryReview->whereHas('user', function($innerQuery) {
                $innerQuery->where('users.name', 'LIKE', '%' . trim(request('search')) . '%');
            });
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["reviews"] = $queryReview->paginate(10);
            
        return view('admin.reviews.index')->with('data', $data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {

        $result = ["type" => "success", "message" => "Avaliação eliminada."];
        
        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $review = Review::find($request->id);

            $review->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["message"] = "Erro ao eliminar avaliação.";
        }

        $returnLink = '/admin/reviews/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/products';

        return redirect($returnLink)->with($result["type"], $result["message"]);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_review';
        
        $data["review"] = Review::find($id);   
                
        $data["statuses"] = Status::all();
        $data["status"] = $data["review"]->status;

        return view($modalName)->with('data', $data);
    }

    public function loadModalDelete($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_delete_review';
        
        $data["review"] = Review::find($id);    

        return view($modalName)->with('data', $data);
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upvote(Request $request)
    {        
        if($request->get('review_id'))
        {
            $review = Review::find($request->get('review_id'));
            $review->up_score = !$review->up_score;
            // Prevent change to both positive
            if($review->down_score && $review->up_score)
                $review->down_score = !$review->down_score;
            $review->save();
            echo true;           
       
        }

        echo false;
    }
    
    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downvote(Request $request)
    {        
        if($request->get('review_id'))
        {
            $review = Review::find($request->get('review_id'));
            $review->down_score = !$review->down_score;
            // Prevent change to both positive
            if($review->up_score && $review->down_score)
                $review->up_score = !$review->up_score;
            $review->save();
            echo true;        
       
        }

        echo false;
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {        
        $review = Review::find($id);
        $review->active = 1;
        $review->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/reviews');
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        $review = Review::find($id);
        $review->active = 0;
        $review->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/reviews');
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
        $this->validate($request, [
            'status' => 'required'
        ]);

        $review = Review::find($id);
        $review->status_id = $request->input('status');

        $review->save();

        return redirect('/admin/reviews/')->with('success', 'Avaliação atualizada.');
    }

    
}