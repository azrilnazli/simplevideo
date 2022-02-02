<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Jobs\GenerateKeyJob;
use App\Jobs\EncodeVideoJob;
use App\Jobs\LiveRecordJob;
use App\Jobs\ConvertToMP4Job;

use App\Models\Video;
use App\Models\Recording;


class VideoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function dashboard(){
        return view('layout')->with(['content' => 'dashboard']);
    }

    public function recordings(){
        $recordings  = Recording::where('work', 0)->orderBy('id', 'DESC')->simplePaginate(20);
        return view('layout',compact('recordings'))->with(['content' => 'recordings']);
    }

    public function recording_delete($id){
        Recording::where('id', $id)->delete();
        $this->rrmdir('recordings/' . $id);
        return redirect('/recordings/index');

    }

    public function live(){
        // check if there's running recording job
        $recording = Recording::where('work',1)->first();
        return view('layout')->with('recording', $recording)->with(['content' => 'live']);
    }

    public function index()
    {
        $videos  = Video::where('work', 0)->orderBy('id', 'DESC')->get();
        
        $encoding  = Video::where('work', 1)->orderBy('id', 'DESC')->get();
        

        return view('layout',compact('videos','encoding') )->with('name', 'Index')->with(['content' => 'index']);
    }

    public function show($id)
    {
        $videos  = Video::orderBy('id', 'DESC')->get();
        $video = Video::where('id', $id)->first();
        return view('layout',compact('videos') )->with('id', $id)->with('name', $video->name)->with(['content' => 'index']);
    }

    public function upload()
    {
        return view('layout')->with(['content' => 'upload']);
    }

    public function delete($id)
    {
        Video::where('id', $id)->delete();
        $this->rrmdir('videos/' . $id);
        return redirect('/video');
    }

    public function rrmdir($dir) { 
        if (is_dir($dir)) { 
          $objects = scandir($dir);
          foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
              if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
               $this->rrmdir($dir. DIRECTORY_SEPARATOR .$object);
              else
                unlink($dir. DIRECTORY_SEPARATOR .$object); 
            } 
          }
          rmdir($dir); 
        } 
      }

    public function store(Request $request)
    {
        if ($request->hasFile('video')) {

            //echo 'ada video';
            $original_filename = $request->file('video')->getClientOriginalName();
            //$original_filename_arr = explode('.', $original_filename);
            //$file_ext = end($original_filename_arr);
            $destination_path = './videos/';
            $file = $original_filename;

            // Retrieve flight by name or create it if it doesn't exist...
            $video = Video::firstOrCreate([
                'name' => $file,
                'work' => 1
            ]);
            $path = "./videos/" . $video->id; 
            mkdir($path);

            // create directory
            $collection = collect(['m3u8','keys']);
            $collection->each( function($item, $key) use ($path) {
                // create directory
                mkdir($path . '/' . $item );
            });

            if ($request->file('video')->move($path, 'original.mp4')) {

                $key = ( new GenerateKeyJob($video) )->onQueue('key');
                dispatch($key);

                $encode = ( new EncodeVideoJob($video) )->onQueue('video');
                dispatch($encode);

                // encode video
                return redirect('/video');

            } else {
                return redirect('/video');
            }
        } else {
            return redirect('/video');
        }
    }

    public function store_video(Request $request){

        $output_dir = "uploads/";
        if(isset($_FILES["video"]))
        {
            $ret = array();
            
        //	This is for custom errors;	
        /*	$custom_error= array();
            $custom_error['jquery-upload-file-error']="File already exists";
            echo json_encode($custom_error);
            die();
        */
            $error =$_FILES["video"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData() 
            if(!is_array($_FILES["video"]["name"])) //single file
            {
                $fileName   = $_FILES["video"]["name"];
                $tmp        = $_FILES["video"]["tmp_name"];
                $this->process_video($fileName, $tmp);
                $ret[] = $fileName;
            }
            else  //Multiple files, file[]
            {
                $fileCount = count($_FILES["video"]["name"]);
                for($i=0; $i < $fileCount; $i++)
                {
                    $fileName = $_FILES["video"]["name"][$i];
                    $this->process_video($fileName, $tmp);
                    $ret[]= $fileName;
                }          
            }
            echo json_encode($ret);
        }
    }

    public function process_video($filename, $tmp)
    {

           $destination_path = './videos/';
           // Retrieve flight by name or create it if it doesn't exist...
           $video = Video::firstOrCreate([
               'name' => $filename,
               'work' => 1
           ]);
           $path = "videos/" . $video->id . "/"; 
           mkdir($path);

           // create directory
           $collection = collect(['m3u8','m3u8/keys']);
           $collection->each( function($item, $key) use ($path) {
               // create directory
               mkdir($path . '/' . $item );
           });

           if ( move_uploaded_file($tmp, $path.'original.mp4')) {

               $key = ( new GenerateKeyJob($video) )->onQueue('key');
               dispatch($key);

               $encode = ( new EncodeVideoJob($video) )->onQueue('video');
               dispatch($encode);

               // encode video
               return TRUE;
           } else {
               return FALSE;
           }
    }

    public function embed(){
        $html = <<<HTML
        <video-js id="player" class="vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true}'>
            <source src="/foldername/manifest.m3u8" type="application/x-mpegURL">
        </video-js>
        <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
        <script src="https://unpkg.com/video.js/dist/video.js"></script>
        <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>

        <script>
            var player = videojs('player');
        </script>
      HTML;
      return view('layout')->with('html', $html)->with(['content' => 'embed']);
    }

    public function ajax(){
   
        //$data['id'] = $_GET['id'];
        $data['recording'] = $_GET['recording'];
        $data['incoming_stream'] =  $_GET['incoming_stream'];
        //$data['incoming_stream'] = "rtmp://hls_video.test:1935/hls/stream";

        switch($data['recording']){
            case 'start':
                $file = "recordings/progress.txt";
                shell_exec("echo '' > $file");
               
                
                // insert into recordings table
               
                $recording = Recording::make([
                    'incoming_stream' => $data['incoming_stream'],
                    'queue' => 'record',
                    'name' => 'recording.mp4',
                    'work' => 1
                ]);
                $recording->save();

                // create directory based on recording id
                $path = "recordings/" . $recording->id . "/"; 
                mkdir($path);

                $data['rand'] = $this->start_record($recording->id, $data['incoming_stream']);
                $data['id'] = $recording->id;

            break;

            case 'stop':
                $data['rand'] = $_GET['rand'];
                $this->stop_record($_GET['rand']);
            break;
        }

        //echo json_encode($data);
        return response()->json($data);
    }

    public function stop_record($rand){

        $cmd = "taskkill /F /IM $rand.exe";
        //$cmd = "pskill $rand.exe";
        
        shell_exec($cmd);
        return TRUE;

    }

    public function start_record($id, $incoming_stream){

        $rand = rand();

        $recording = Recording::find($id);
        $recording->name = $rand;
        $recording->save();
        
        // copy ffmpeg.exe to random name
        $file = 'c:\ffmpeg\bin\ffmpeg.exe';
        $newFile  = 'c:\ffmpeg\bin\/' . $rand . '.exe';

        if (!copy($file, $newFile)) {
            echo "failed to copy $file...\n";
        }

        // Job initialization
        $record = ( new LiveRecordJob($id, $incoming_stream, $rand ) )->onQueue('record');
        $convert = ( new ConvertToMP4Job($id, $rand ) )->onQueue('record');
        
        // chainable 
        dispatch(
            ( $record ) // record from rtmp to mkv
            ->chain([
                $convert // convert to MP4
            ])
        );
  
        // rand() value
        return $rand;
    }

    function progress(){
   
        $file = "recordings/progress.txt";
        $handle = fopen($file, "r");
        if ($handle) {
            $i=0;
            $j=0;
            while (($line = fgets($handle)) !== false) {
                // process the line read.
                $line = str_replace("\n","", $line);
                $line = str_replace("/s","", $line);
              
                $rows[$i][$j] = $line;

                if( str_contains($line, 'frame=') ){
                    $i++;
                }
                $j++;
            }
        
            fclose($handle);
        } else {
            // error opening the file.
        }
       
        $row = end($rows);
        $progress['status'] = "<i class=\"fas fa-sync fa-spin text-light\"></i>  Recording";
        foreach($row as $k => $v){
            $arr = explode('=', $v);

            if( $arr[0] == 'total_size' ){
                $arr[1] = round($arr[1]/(1024*1024)) . " Mb"; // equal to MB
            }

            if( $arr[0] == 'out_time' ){
                $t = explode(':', $arr[1]);
                $t[2] = round($t[2]);
                $arr[1] = "$t[0]:$t[1]:$t[2]";
            }

            $progress[$arr[0] ] = $arr[1];
        }
      
        return response()->json($progress);
    }
}
;