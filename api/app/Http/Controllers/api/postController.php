<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\api\BaseController as Base;
use Illuminate\Http\Request;
use App\Posts;
use App\User;
use Validator;
use Response;
use DB;
use Gate;
use Auth;

class postController extends base
{

	public function index() 
	{
		
		// get all post for user login 
		$post_user = Posts::all()->where('kind_id', auth()->user()->id);

        if (Gate::allows('kind', Auth::user())) {
        	return $this->sendResponse($post_user->toArray(), ' gggggg');
        }

        if (Gate::denies('kind', Auth::user())) {
            return $this->sendError('Sorry you can\'t open this page');
        	// $this->sendResponse($post_user->toArray(), ' hgfffg');
        }


	}


    public function store(Request $request) {
        $input = $request->all();
        $input['kind_id'] = auth()->user()->id;
        $val = Validator::make($input,[
            'name' => 'required',
            'description' => 'required',
            ]);

        if($val -> fails()){
            return $this->sendError('error validation', $val ->errors());
        }

        $post = Posts::create($input);
        return $this->sendResponse($post->toArray,'Post Create Succesfully');

    }	

    public function show($id) {

        $post = Posts::find($id);

        if(is_null($post)){
            return $this->sendError('Book not found');
        }

        
        return $this->sendResponse($book->toArray,'Book read Succesfully');

    }

    public function update(Request $request, Posts $post) {
        $input = $request->all();
        $val = Validator::make($input,[
            'name' => 'required',
            'description' => 'required',
            ]);

        if($val -> fails()){
            return $this->sendError('error validation', $val ->errors());
        }

        $post->name = $input['name'];
        $post->description = $input['description'];
        return $this->sendResponse($post->toArray,'Post Update Succesfully');

    } 

    public function destroy($id, Posts $post)
    {
        $post->delete();
        return $this->sendResponse($post->toArray, 'Post Delete Succesfully');
    }

}