<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\GenerateKeyJob;
use App\Jobs\EncodeVideoJob;
use App\Models\Video;


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
        return view('dashboard');
    }

    public function index()
    {
        $videos  = Video::where('work', 0)->orderBy('id', 'DESC')->get();
        $encoding  = Video::where('work', 1)->orderBy('id', 'DESC')->get();
        return view('index',compact('videos','encoding') )->with('name', 'Index');
    }

    public function show($id)
    {
        $videos  = Video::orderBy('id', 'DESC')->get();
        $video = Video::where('id', $id)->first();
        return view('index',compact('videos') )->with('id', $id)->with('name', $video->name);
    }

    public function upload()
    {
        return view('upload');
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

      return view('embed')->with('html', $html);
    }
}
