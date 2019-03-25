@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class='image'>
      <img class="img-responsive" alt="" src="/images/{{ $image->image }}" />


@auth
  <section id="comment">
      <form action="{{ url('comments') }}" class="form-image-upload" method="POST">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="imgid" value="{{ $image->id }}">

        <label> Rate the image:
          <input type="number" name="rating" min="1" max="5" value="5">
        </label>
        <label> Write a comment:
          <textarea class="comment" name="comment" rows="7" laceholder="Write a comment..."></textarea>
        </label>
        <button type="submit" class="relative submit edit-icon btn btn-success">
          <i class="fas fa-save"></i> Add comment
        </button>
      </form>
    </section>
@endauth
    </div>

  <ul class="commentlist">
    @foreach($comments as $comment)
      <li>
        <span class="rating">{{ $comment->rating }} <i>Stars</i></span>
        <span class="comment">{{ $comment->comment }} - <strong>by {{$comment->username}} </strong></span>
      </li>
    @endforeach
  </ul>

  </div> <!-- row / end -->
</div> <!-- container / end -->
@endsection
