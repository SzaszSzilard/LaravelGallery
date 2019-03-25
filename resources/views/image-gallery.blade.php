@extends('layouts.app')

@section('content')
<div class="container">


  <script type="text/javascript">
    var showedit = false;
  </script>

  @if(Route::current()->getName() == 'mygallery' )

  <script type="text/javascript">
    var showedit = true;
  </script>
  @endif


  @auth
    <h3>Upload your new images at the following form</h3>
    <form action="{{ url('gallery') }}" class="form-image-upload" method="POST" enctype="multipart/form-data">


        {!! csrf_field() !!}


        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
        @endif


        <div class="row">
            <div class="col-md-5">
                <strong>Title:</strong>
                <input id="imgtitle" type="text" name="title" class="form-control" placeholder="Title">
            </div>
            <div class="col-md-5">
                <strong>Image:</strong>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="col-md-2">
                <br/>
                <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </div>


    </form>
    @endauth

    <!--  -->
    <section id="sort">
      <br>
      <h2>Filter</h2>
      <form class='nosubmit' action="{{ url('getajaximages') }}" method="POST">
        <input type="hidden" name="_method" value="get">
        {!! csrf_field() !!}

        <input type="text" name="search" value="">
        <select class="order" name="order">
          <option value="asc">asc</option>
          <option value="desc">desc</option>
        </select>
        <button type="submit" class="relative filterimg close-icon btn btn-success">
          <i class="fas fa-search"></i> Filter images
        </button>
      </form>
    </section>
    <!--  -->

    <div id='gallerywrapper' class="row">
    <div class='gallery'>


            @if($images->count())
                @foreach($images as $image)

                  @if(Route::current()->getName() == 'mygallery' )

                    @if ($image->userid == Auth::user()->id)
                    <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                        <a class="thumbnail fancybox" rel="ligthbox" href="/images/{{ $image->image }}">
                            <img class="img-responsive" alt="" src="/images/{{ $image->image }}" />
                        </a>
                        <label class="ffooccuuss" for='{{$image->id}}'><i class="fas fa-pen"></i></label>
                        <span id='{{$image->id}}' class='title text-muted' contenteditable="true">{{ $image->title }}</span>
                        <!-- <small class='text-muted'>{{ $image->title }}</small> -->
                        <span class='uploader'><b>Uploader:</b> {{ $image->username }}</span>
                        <span class='comment_no'><b>Number of comments:</b> {{ $image->number_of_comment }}</span>
                        <span class='avg_rating'><b>Average rating:</b> {{ $image->avg_rating }}</span>
                        <a class="imagepage" href="/imagepage/{{ $image->id }}">‘view image’ page</a>
                        <!-- <strong>{{ $image->username }}</strong> -->


                        <form class='deleteform nosubmit' action="{{ url('images',$image->id) }}" method="POST">
                          <input type="hidden" name="_method" value="delete">
                          {!! csrf_field() !!}
                          <button type="submit" class="deleteimg close-icon btn btn-danger">
                            <i class="fas fa-window-close"></i>
                          </button>
                        </form>

                        <form class='updateform nosubmit' action="{{ url('images',$image->id) }}" method="POST">
                          <input type="hidden" name="_method" value="PUT">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <button type="submit" class="editimg edit-icon btn btn-success">
                            <i class="fas fa-pen"></i>
                          </button>
                        </form>
                    </div> <!-- col-6 / end -->
                    @endif

                  @else
                  <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                      <a class="thumbnail fancybox" rel="ligthbox" href="/images/{{ $image->image }}">
                          <img class="img-responsive" alt="" src="/images/{{ $image->image }}" />
                      </a>
                      <small class='text-muted'>{{ $image->title }}</small>
                      <span class='uploader'><b>Uploader:</b> {{ $image->username }}</span>
                      <span class='comment_no'><b>Number of comments:</b> {{ $image->number_of_comment }}</span>
                      <span class='avg_rating'><b>Average rating:</b> {{ $image->avg_rating }}</span>
                      <a class="imagepage" href="/imagepage/{{ $image->id }}">‘view image’ page</a>
                  </div> <!-- col-6 / end -->
                  @endif

                @endforeach
            @endif

        </div> <!-- list-group / end -->

        {{ $images->links() }}
        @if(Route::current()->getName() != 'mygallery' )
        @endif

    </div> <!-- row / end -->
</div> <!-- container / end -->


<script type="text/javascript">
    var items = $('.gallery > div');
    var item = items[Math.floor(Math.random()*items.length)];

    $(item).addClass('featured');

    $(document).ready(function(){
        $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
        });
    });
    // $(function(){
      $('.nosubmit').submit(function(e){
        e.preventDefault();
      });

      // filter on writing
      $("#sort input[type='text']").keyup(function(event){
          event.stopPropagation();

          let url = $(this).parent().attr('action');
          let order = $(this).next().find('option:selected').val();
          let search = $(this).val();
          let token = $(this).prev().val();
          let method = $(this).prev().prev().val();

          if (search == '')
            search = '---';

          var all = showedit ? 'no' : 'yes';

          url += '/' + search + '/' + order + '/' + all;

          $.ajax({
            type: "POST",
            url: url,
            data: {
              "_method": method,
              "_token": token,
            },
            success: function(response){
              $('#gallerywrapper').html(response)

              if (!showedit) {
                $('.gallery .nosubmit').remove();

                $('.my_title').prev().remove();
                $('.my_title').remove();
              } else {
                $('.my_title').next().remove();
              }
              // console.log('loaded');
            }
          });
      })

      // fliter on click
      $('.filterimg').click(function(e){
        event.stopPropagation();

        let url = $(this).parent().attr('action');
        let order = $(this).prev().find('option:selected').val();
        let search = $(this).prev().prev().val();
        let token = $(this).prev().prev().prev().val();
        let method = $(this).prev().prev().prev().prev().val();

        if (search == '')
          search = '---';

        var all = showedit ? 'no' : 'yes';

        url += '/' + search + '/' + order + '/' + all;

        $.ajax({
          type: "POST",
          url: url,
          data: {
            "_method": method,
            "_token": token,
          },
          success: function(response){
            $('#gallerywrapper').html(response);

            if (!showedit) {
              $('.gallery .nosubmit').remove();

              $('.my_title').prev().remove();
              $('.my_title').remove();
            } else {
              $('.my_title').next().remove();
            }
            // console.log('loaded');
          }
        });
      });

      // $('.deleteimg').click(function(e){
      $('body').on('click','.deleteimg',function(e){
        event.stopPropagation();

        let url = $(this).parent().attr('action');
        let token = $(this).prev().val();
        let method = $(this).prev().prev().val();

        let image = $(this).parent().parent();

        $.ajax({
          type: "POST",
          url: url,
          data: {
            "_method": method,
            "_token": token,
          },
          success: function(response){
            image.fadeOut();

            console.log(response);
          }
        });
      });

      $('.gallery div .title').focusout(function(event){
        event.stopPropagation();

        let url = $(this).parent().children('.updateform').attr('action');
        let method = $(this).parent().children('.updateform').find("[name=_method]").val();
        let token = $(this).parent().children('.updateform').find("[name=_token]").val();

        let title = $(this).text();

        if (title=='') {
          title = 'name needed';
        }

        $(this).text(title);

        url += '/' + title;

        $.ajax({
          type: "POST",
          url: url,
          data: {
            "_method": method,
            "_token": token,
          },
          success: function(response){
            // console.log(response);
            console.log('successuflly updated');
          }
        });
      })

      $('.editimg').click(function(e){
        event.stopPropagation();

        let url = $(this).parent().attr('action');
        let token = $(this).prev().val();
        let method = $(this).prev().prev().val();

        let title = $(this).parent().parent().find('.title').text();

        if (title=='') {
          title = 'dont leave it empty, maybe fix it in production';
        }

        $(this).parent().parent().find('.title').text(title);

        url += '/' + title;

        $.ajax({
          type: "POST",
          url: url,
          data: {
            "_method": method,
            "_token": token,
          },
          success: function(response){
            // console.log(response);
          }
        });
      });

      // set a jquery field check
      $('#imgtitle').parents('form:first').submit(function(e){
        if ($('#imgtitle').val().length < 5 ) {
          alert("Too short filename! It must be at least 5 characters!");

          e.preventDefault();
          return false;
        }
      })

      $('#username').parents('form:first').submit(function(e){
        if ($('#username').val().length < 5 ) {
          alert("Too short filename! It must be at least 5 characters!");

          e.preventDefault();
          return false;
        }
      })

      $('#username').parents('form:first').validate({
        rules: {
            username: {
                minlength: 5,
                required: true
            }
        }
      })

      // $('.ffooccuuss').click(function(){
      //   console.log($(this).next());
      //   setTimeout(function() {
      //       $(this).next().focus();
      //   }, 0);
      // })
    // })
</script>

@endsection
