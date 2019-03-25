<div class="gallery">
        @if($images->count())
            @foreach($images as $image)
              <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                  <a class="thumbnail fancybox" rel="ligthbox" href="/images/{{ $image->image }}">
                      <img class="img-responsive" alt="" src="/images/{{ $image->image }}" />
                  </a>

                  <label class="ffooccuuss" for='{{$image->id}}'><i class="fas fa-pen"></i></label>
                  <span id='{{$image->id}}' class='my_title title text-muted' contenteditable="true">{{ $image->title }}</span>
                  <small class='text-muted'>{{ $image->title }}</small>
                  <span class='uploader'><b>Uploader:</b> {{ $image->username }}</span>
                  <span class='comment_no'><b>Number of comments:</b> {{ $image->number_of_comment }}</span>
                  <a class="imagepage" href="/imagepage/{{ $image->id }}">‘view image’ page</a>

                  <form class='nosubmit' action="{{ url('images',$image->id) }}" method="POST">
                    <input type="hidden" name="_method" value="delete">
                    {!! csrf_field() !!}
                    <button type="submit" class="deleteimg close-icon btn btn-danger">
                      <i class="fas fa-window-close"></i>
                    </button>
                  </form>

                  <form class='nosubmit' action="{{ url('images',$image->id) }}" method="POST">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="editimg edit-icon btn btn-success">
                      <i class="fas fa-pen"></i>
                    </button>
                  </form>
              </div> <!-- col-6 / end -->
            @endforeach
        @endif

        <script>
          // $(function(){
          //   if (!showedit) {
          //       console.log($('.nosubmit'));
          //     $('.nosubmit').remove();
          //   }
          // })
        </script>

  </div> <!-- list-group / end -->

  {{ $images->links() }}
