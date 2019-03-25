<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;

class CommentController extends Controller
{

  public function index() {
    //
  }

  public function get($imgid)
  {
    // $comments = Comment::where("imgid",$imgid)->get();
    $comments = Comment::join('users', 'users.id', '=', 'comment.userid')
          ->select('comment.*', 'users.username')
          ->where('imgid', $imgid) // maybe it should be escaped havent had any time to research it
          ->get();

    return $comments;
  }

  public function get_count($imgid)
  {
    // $comments = Comment::where("imgid",$imgid)->get();
    $comments = Comment::join('users', 'users.id', '=', 'comment.userid')
          ->where('imgid', $imgid) // maybe it should be escaped havent had any time to research it
          ->count();

    return $comments;
  }

  public function get_avg_rating($imgid)
  {
    $comments = Comment::join('images', 'images.id', '=', 'comment.imgid')
          ->where('imgid', $imgid) // maybe it should be escaped havent had any time to research it
          ->avg('rating');

    return $comments;
  }

  public function upload(Request $request)
  {
      $input['imgid'] = $request->imgid;
      $input['userid'] = Auth::user()->id;
      $input['rating'] = $request->rating;
      $input['comment'] = $request->comment;

      Comment::create($input);

    return back()
      ->with('success','Image Uploaded.');
  }
}
