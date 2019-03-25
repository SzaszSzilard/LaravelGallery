<div class='featured img'>
  <div class='left'>
    <a class="thumbnail fancybox" rel="ligthbox" href="/images/{{ $image->image }}">
        <img class="img-responsive" alt="" src="/images/{{ $image->image }}" />
    </a>
  </div>

    <div class='right'>
      <small class='text-muted'>{{ $image->title }}</small>
      <span class='uploader'><b>Uploader:</b> {{ $image->username }}</span>
      <span class='comment_no'><b>Number of comments:</b> {{ $image->number_of_comment }}</span>
      <a class="imagepage" href="/imagepage/{{ $image->id }}">‘view image’ page</a>
    </div>
<div class="clear"></div>
</div> <!-- col-6 / end -->
