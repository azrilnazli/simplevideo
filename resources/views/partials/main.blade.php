<link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Dashboard</h1>
    </div>
 
    
    <form name="upload" method="POST" action="/store" enctype="multipart/form-data">
   
    <div class="input-group">
        <input name="video" type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
        <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">Upload</button>
    </div>
    </form>

    <hr />
    @if(isset($id))
    <div class="col-6">
      <video-js id="my_video_1" class="vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true}'>
          <source src="/videos/{{ $id }}/keys/manifest.m3u8" type="application/x-mpegURL">
      </video-js>
  
      <script src="https://unpkg.com/video.js/dist/video.js"></script>
      <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>
  
      <script>
          var player = videojs('my_video_1');
      </script>
    </div>
    @endif

    <br />
    <div class="row">
      <div class="col-md-6 col-md-offset-1">
        <ol class="list-group">
          @foreach($videos as $video)
            <li class="list-group-item">{{ $video->id }} - <a href="/video/{{ $video->id }}">{{ $video->name }}</a></li>
          @endforeach
        </ol>
      </div>
    </div>

    

</main>



