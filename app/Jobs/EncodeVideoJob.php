<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Artisan;
use App\Models\Video;
use DB;
use Cache;



class EncodeVideoJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(5);
        $path =  app()->basePath('public') . "/videos/" . $this->video->id;

        ########################################
        $ffmpeg = "ffmpeg";
        $input = $path . "/original.mp4";
        $hls_key_info_file = $path . "/keys/enc.keyinfo";
        $hls_time = 10; // seconds
        $hls_flags = "periodic_rekey";
        $hls_playlist_type = "vod";
        $manifest = $path . "/keys/manifest.m3u8";

        $cmd = "$ffmpeg -i $input -hls_key_info_file $hls_key_info_file -hls_time $hls_time -hls_flags $hls_flags -hls_playlist_type $hls_playlist_type $manifest";
        shell_exec($cmd);
        ########################################

        echo "Processing Video  " . PHP_EOL;
        
        $this->video->work = 0;
        $this->video->save();
        echo " Work is done  " . PHP_EOL;
        
        // terminate key generator
    }
}
