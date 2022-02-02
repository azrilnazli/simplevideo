<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-video text-danger"></i> Recordings</h1>
</div>

<div class="container">
    <div class="row">

        <table class="table">
            <thead>
                <th style="width:10px">#</th>
                <th>Stream</th>
                <th>Recorded on</th>
                <th>Actions</th>
            </thead>
            
            <tbody>
                @foreach($recordings as $record)
                <tr>
                    <td><strong>{{$record->id}}</strong></td>
                    <td>{{$record->incoming_stream}}</td>
                    <td>{{$record->created_at}}</td>
                    <th class="text-center">
                      
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal_{{$record->id}}"><i class="fas fa-search"></i></a>
                        <a href="/recordings/{{$record->id}}/delete"><i class="fas fa-trash text-danger"></i></a>
                       

                    </th>
                </tr>
                @endforeach
            </tbody>
        </table>
        @include('pagination.default', ['paginator' => $recordings])
    </div>
</div>
<!-- Button trigger modal -->

  
<link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
<script src="https://unpkg.com/video.js/dist/video.js"></script>
<script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>

<!-- Modal -->
@foreach($recordings as $record)
<div class="modal fade" id="modal_{{ $record->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-lg modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $record->incoming_stream }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
  
            <video-js id="player_{{ $record->id }}" class="vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true}'>
                <source src="/recordings/{{ $record->id }}/recording.mp4" type="video/mp4" />
            </video-js>
     
          
            <script>
                var player = videojs('player_{{ $record->id }}');
            </script>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
@endforeach
  
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  