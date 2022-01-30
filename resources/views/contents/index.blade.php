<link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Video : {{ $name }}</h1>
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
    {{ $app->path() . DIRECTORY_SEPARATOR  . 'videos' .  DIRECTORY_SEPARATOR . $id }} </strong></td>
</tr>

</table>
</div>

@endif

<br />
<div class="row">
  <div class="col-md-9 col-md-offset-1">
    <span style="font-size: 1em; color: rgb(71, 255, 102);"><i class="fas fa-check"></i></span>
    <b> Ready</b>
  
    <ol>
      @foreach($videos as $video)
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
              [ <a href="/video/{{ $video->id }}/delete"><i class="fas fa-trash"></i></a> ]
            </small>
        </li>
      @endforeach
    </ol>
  </div>
</div>


@if(isset($encoding))
<div class="row">
  <div class="col-md-9 col-md-offset-1">
    <span style="font-size: 1em; color: rgb(255, 71, 71);">
      <i class="fas fa-exclamation-triangle"></i> 
    </span>
    <b> Processing</b>
  
    <ol>
      @foreach($encoding as $video)
        <li>
          [{{ $video->id }}]
          {{ $video->name }}
          <small>
              [  <i class="fas fa-sync fa-spin"></i>  ]
            </small>
        </li>
      @endforeach
    </ol>
  </div>
</div>
@endif
<hr />
<a class="lead" href="/video">[ refresh ] </a>







