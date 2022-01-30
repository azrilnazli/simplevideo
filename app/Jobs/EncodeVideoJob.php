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
        echo "Processing Video  " . PHP_EOL;
        $path =  app()->basePath('public') . "/videos/" . $this->video->id;

        ########################################
        $ffmpeg = "ffmpeg";
        $input = $path . "/original.mp4";
        $hls_key_info_file = $path . "/m3u8/enc.keyinfo";
        $hls_time = 10; // seconds
        $hls_flags = "periodic_rekey";
        $hls_playlist_type = "vod";
        $manifest = $path . "/m3u8/manifest.m3u8";

        $cmd = "$ffmpeg -i $input -hls_key_info_file $hls_key_info_file -hls_time $hls_time -hls_flags $hls_flags -hls_playlist_type $hls_playlist_type $manifest";
        shell_exec($cmd);

        // move .key to keys folder
        $this->moveType("key,keyinfo", $path . "/m3u8/", $path . "/m3u8/keys/");

        //generate sample player
        $this->generatePlayer($path);

        // zip the folder ! need to install ZipArchive
        //$this->zip($path); 

        ########################################

       
        
        $this->video->work = 0;
        $this->video->save();
        echo " Work is done  " . PHP_EOL;
        
        // terminate key generator
    }

    function moveType ($ext, $src, $dest) {
        // (A) CREATE DESTINATION FOLDER
        if (!file_exists($dest)) { 
          mkdir($dest); 
          echo "$dest created\r\n";
        }
      
        // (B) GET ALL FILES
        $files = glob($src."*.{".$ext."}", GLOB_BRACE);
      
        // (C) MOVE
        if (count($files)>0) { foreach ($files as $f) {
          $moveTo = $dest . basename($f);
          echo rename($f, $moveTo) 
            ? "$f moved to $moveTo\r\n"
            : "Error moving $f to $moveTo\r\n";
        }}
      }

      function generatePlayer($path){
          $html = <<<HTML
            <video-js id="player" class="vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true}'>
                <source src="manifest.m3u8" type="application/x-mpegURL">
            </video-js>
            <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
            <script src="https://unpkg.com/video.js/dist/video.js"></script>
            <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>

            <script>
                var player = videojs('player');
            </script>
          HTML;

          file_put_contents($path . "/m3u8/index.html" , $html);
      }

    function zip($path){
       

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open( $path . 'file.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
        // Get real path for our path
        $rootPath = realpath($path . '/m3u8/');
       
        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }
      
}
