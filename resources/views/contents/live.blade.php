


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Livestream</h1>
</div>


<video-js id="player" class="vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{ "autoplay": true,"fluid": true}'>
    <source src="http://hls_video.test:8080/hls/stream.m3u8" type="application/x-mpegURL">
</video-js>
<link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />

<script src="https://unpkg.com/video.js/dist/video.js"></script>
<script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>

<script>
    var player = videojs('player');
</script>
<div class=" mt-1">
  <a href="/live/stream/record" class="btn btn-primary">Record</a>
</div>
