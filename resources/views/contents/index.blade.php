

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2"> {{ $name }}</h1>
</div>

<!--
<form name="upload" method="POST" action="/store" enctype="multipart/form-data">

<div class="input-group">
    <input name="video" type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
    <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">Upload</button>
</div>
</form>
-->

@if(isset($id))

  <video-js id="player" class="vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true}'>
      <source src="/videos/{{ $id }}/m3u8/manifest.m3u8" type="application/x-mpegURL">
  </video-js>
  <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />

  <script src="https://unpkg.com/video.js/dist/video.js"></script>
  <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>

  <script>
      var player = videojs('player');
  </script>


<div class=" mt-1">
<table class="table">
  <tr>
    <td style="width:20px" class="bg-info">ID</td>
    <td class="bg-warning "><strong>{{ $id }}</strong></td>
  </tr>
<tr>
  <td style="width:20px" class="bg-info">Name</td>
  <td class="bg-warning"><strong>{{ $name }}</strong></td>
</tr>

<tr>
  <td style="width:20px" class="bg-info">Assets</td>
  <td class="bg-warning"><strong>
    {{ getcwd()   .  DIRECTORY_SEPARATOR    . 'videos' .  DIRECTORY_SEPARATOR . $id }} </strong></td>
</tr>

</table>
</div>

@endif

  <table class="table">
    <tr>
      <td colspan=2 style="background-color: lightblue">
        <h5><i class="fas fa-check text-success"></i> Ready to view</h5>
      </td>
    </tr>
    <tr>
      @foreach($videos->chunk( round((count($videos)/2)) ) as $chunk)
      <td style="background-color: #eaeaea">
        <ul style="list-style-type:none ">
          @foreach($chunk as $video)
          <li>
            @if( isset($id) AND $id == $video->id)
            <strong>
            [{{ $video->id }}]
            </strong>
            @else 
            [{{ $video->id }}]
            @endif
            <a href="/video/{{ $video->id }}">{{ $video->name }}</a>
            <small>
                [ <a href="/video/{{ $video->id }}/delete"><i class="fas fa-trash-alt text-danger"></i></a> ]
              </small>
          </li>
          @endforeach
        </ul>
      </td>
      @endforeach
    </tr>
  </table>


@if( isset($encoding) AND !empty($encoding) )
<table class="table">
  <tr>
    <td colspan=2 style="background-color:yellow">
      <h5>  <span style="font-size: 1em; color: rgb(255, 71, 71);">
        <i class="fas fa-exclamation-triangle"></i> 
      </span> Still processing</h5>
    </td>
  </tr>
  <tr>
    @foreach($encoding->chunk( round((count($encoding)/2)) ) as $chunk)
    <td style="background-color: lightyellow">
      <ul style="list-style-type:none ">
        @foreach($chunk as $video)
        <li>
    
          [{{ $video->id }}]
         
          <a href="/video/{{ $video->id }}">{{ $video->name }}</a>
          <small>
              [  <i class="fas fa-sync fa-spin"></i> ]
            </small>
        </li>
        @endforeach
      </ul>
    </td>
    @endforeach
  </tr>
</table>
@endif
<a class="lead" href="/video">[ <i class="fas fa-retweet"></i> refresh ] </a>







