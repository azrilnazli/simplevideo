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

    public function index()
    {
        $videos  = Video::orderBy('id', 'DESC')->get();
        return view('index',compact('videos') );
    }

    public function show($id)
    {
        $videos  = Video::orderBy('id', 'DESC')->get();
        return view('index',compact('videos') )->with('id', $id);
    }

    public function upload()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('video')) {

            //echo 'ada video';
            $original_filename = $request->file('video')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './videos/';
            $file = basename($original_filename, '.' . $file_ext) .'-' . time() . '.' . $file_ext;

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
}
