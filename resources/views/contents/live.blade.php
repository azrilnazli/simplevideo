


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Livestream</h1>
</div>



<link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />

<script src="https://unpkg.com/video.js/dist/video.js"></script>
<script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<table class="table bg-black text-light">
  <tr>
    <td width="540">
      <video-js id="player" class="vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{ "autoplay": true,"fluid": true}'>
        <source src="http://hls_video.test:8080/hls/stream.m3u8" type="application/x-mpegURL">
    </video-js>
    </td>
    <td id ="processing">
      <div >
        <table>
          <tr>
            <td colspan="3"><strong><span id="status" class="text-success">preparing data...</span></strong></td>
          </tr>

          <tr>
            <td><span class="text-light">Bitrate</span></td>
            <td>:</td>
            <td><span id="bitrate" class="text-danger"><img src="/img/loading.gif" /></span></td>
          </tr>

          <tr>
            <td><span class="text-light">Size</span></td>
            <td>:</td>
            <td><span id="total_size" class="text-danger"><img src="/img/loading.gif" /></span></td>
          </tr>

          <tr>
            <td><span class="text-light">Length</span></td>
            <td>:</td>
            <td><span id="out_time" class="text-danger"><img src="/img/loading.gif" /></span></td>
          </tr>

          <tr>
            <td><span class="text-light">Speed</span></td>
            <td>:</td>
            <td><span id="speed" class="text-danger"><img src="/img/loading.gif" /></span></td>
          </tr>

        </table>
      </div>
    </td>
  </tr>
</table>


<div class=" mt-1">
  <div class="input-group mb-3">
    <span class="input-group-text bg-primary text-light" id="basic-addon3">Incoming Stream</span>
    <input readonly type="text" class="form-control" id="incoming_stream" aria-describedby="basic-addon3" value="rtmp://hls_video.test:1935/hls/stream">
    <a href="#" id="record" class="btn btn-warning">Record</a>
    <a href="#" id="stop" class="btn btn-danger">Stop</a>
  </div>
</div>


  
<div class="mt-2 col" id="status"></div>


<script>
  var player = videojs('player');
 </script>

<input type="hidden" id="rand" value="" />


<script>
  $(document).ready(function() { // when JQuery is ready
    var incoming_stream = $("#incoming_stream").val(); // value get
    var id; // value get
    var rand; // value get
    var recording;
    var count = 1;
    var interval;

    $("#stop").hide();
    $("#processing").hide();

    $("#record").click(function() { // click button event 

      $("#processing").show(1000);
      $("#stop").show();
      $("#record").hide();

      interval = setInterval(function() {
          //$("#progress").load("/progress");  
          $.ajax({
              type: "GET",
              url: "/progress",
              cache: false,
              success: function(data) {
                $("#status").html(data.status) // data returned by server
                $("#bitrate").html(data.bitrate) // data returned by server
                $("#total_size").html(data.total_size) // data returned by server
                $("#out_time").html(data.out_time) // data returned by server
                $("#speed").html(data.speed) // data returned by server
              },
              error: function(xhr, status, error) {
                console.error(xhr); // javascript error log to console()
              }
         }); //  ajax submission & response
    
      }, 500);

      $.ajax({
        type: "GET",
        url: "/ajax",
        data: {
            incoming_stream:  $("#incoming_stream").val(),
            recording: 'start',
        },
        cache: false,
        success: function(data) {
      
          $("#rand").val(data.rand);
          $("#id").val(data.id);
         // alert(data.id);
         // alert(data.rand);

        },
        error: function(xhr, status, error) {
          console.error(xhr); // javascript error log to console()
        }
      }); //  ajax submission & response

    }); // #submit click

    $("#stop").click(function() { // click button event 
      $("#stop").hide();
      $("#record").show();
      $("#processing").hide(1000);

      clearInterval(interval);
      
      $.ajax({
        type: "GET",
        url: "/ajax",
        data: {
            recording: 'stop',
            rand: $("#rand").val(),
            incoming_stream:  $("#incoming_stream").val(),
            
        },
        cache: false,
        success: function(data) {
         // alert('stop');
          //alert(data.rand); // data returned by server
        },
        error: function(xhr, status, error) {
          console.error(xhr); // javascript error log to console()
        }
      }); //  ajax submission & response
    }); // #stop.click()

    

     

  }); // document.ready
 </script>
