<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Images;
use App\Comment;

class ImagesController extends Controller
{
    /**
     * Listing Of images
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // if(\Auth::check()){
      if (\Request::is('mygallery')) {
        $id = Auth::user()->id;
        $images = Images::join('users', 'users.id', '=', 'images.userid')
              ->select('images.*', 'users.username')
              ->where('users.id', $id)
              ->paginate(4);
     } else {
       $images = Images::join('users', 'users.id', '=', 'images.userid')
             ->select('images.*', 'users.username')
             // ->get();
             ->paginate(4);
     }

      // $comments = app('App\Http\Controllers\CommentController')->get_count($id);
      // $newimages = [];
      // $newimages[] = $image;
      foreach ($images as $image) {
        $image->number_of_comment = app('App\Http\Controllers\CommentController')->get_count($image->id);
        $image->avg_rating = app('App\Http\Controllers\CommentController')->get_avg_rating($image->id);

        $image->avg_rating = ( $image->avg_rating == '' ) ? 0 : $image->avg_rating;
      }

      // $images = new \stdClass;
      // $images->total = count($newimages);
      // $images->items = $newimages;
      // $images = $newimages;

      // return view('image-gallery',compact('images'));

    	// $images = Images::get();

      // $this->get_featured($id);
    	return view('image-gallery',compact('images'));
    }

    public function get($id)
    {
      $image = Images::where("id",$id)->first();
      // return view('image-page',compact('image'));

      // $comment = Comment::get($id);
      $comments = app('App\Http\Controllers\CommentController')->get($id);

      return view('image-page',compact('image','comments'));
    }

    public function get_by_username_or_title_sorted($search,$order,$all)
    {
      // $order must be asc or desc
      if ($order != 'asc' && $order != 'desc')
        return '';

       $search = ($search == '---') ? '' : $search;

       if ($all == 'no') {
         $id = Auth::user()->id;
         $images = Images::join('users', 'users.id', '=', 'images.userid')
               ->select('images.*', 'users.username')
               ->where('users.id', $id)
               ->where(function($query) use ($search) {
                 $query->where('title', 'like', '%' . $search . '%');
                 $query->orWhere('username', 'like', '%' . $search . '%');
               })
               ->orderBy('images.updated_at', $order)
               ->paginate(1000);
               // ->get();

      } else {
        $images = Images::join('users', 'users.id', '=', 'images.userid')
              ->select('images.*', 'users.username')
              ->where('title', 'like', '%' . $search . '%')
              ->orWhere('username', 'like', '%' . $search . '%')
              ->orderBy('images.updated_at', $order)
              ->paginate(1000);
              // ->get();
      }

      foreach ($images as $image) {
        $image->number_of_comment = app('App\Http\Controllers\CommentController')->get_count($image->id);
        $image->avg_rating = app('App\Http\Controllers\CommentController')->get_avg_rating($image->id);

        $image->avg_rating = ( $image->avg_rating == '' ) ? 0 : $image->avg_rating;
      }

      // return view('image-gallery',compact('images'));
      // return $images;

      return view('filtered-images',compact('images'));
    }

    public function get_by_title($title)
    {
      $images = Images::get();
      return view('image-gallery',compact('images'));
    }

    /**
     * Upload image function
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
    	$this->validate($request, [
    		'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input['userid'] = Auth::user()->id;

        $input['image'] = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $input['image']);

        $input['title'] = $request->title;

        Images::create($input);

    	return back()
    		->with('success','Image Uploaded.');
    }

    /**
     * update image function
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, $title)
    {
        $input = Images::find($id);
        $input['title'] = $title;

        $input->save();

      return back()
        ->with('success','Image Updated.');
    }

    /**
     * Remove Image function
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$image = Images::find($id);

      $id = Auth::user()->id;

      // echo $id . ' ';
      // echo $image->userid;
      // echo '  logged in id' . $id;

      if ($image->userid == $id) {
        $image->delete();
        echo "deleted";
      } else {
        echo "not deleted";
      }

    	// return back()
    	// 	->with('success','Image deleted.');
    }

    public static function get_featured()
    {
      $image = Images::inRandomOrder()->first();

      $image->username = app('App\Http\Controllers\UsersController')->getthis($image->userid);
      $image->username = $image->username->username;
      $image->number_of_comment = app('App\Http\Controllers\CommentController')->get_count($image->id);
      $image->avg_rating = app('App\Http\Controllers\CommentController')->get_avg_rating($image->id);
      $image->avg_rating = ( $image->avg_rating == '' ) ? 0 : $image->avg_rating;

      return view('featured',compact('image'));
    }
}
