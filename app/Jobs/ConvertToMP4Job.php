<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Artisan;
use App\Models\Video;
use DB;
use Cache;
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


class ConverttoMP4Job extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $channel;
    public $rand;
    public $id;

    public function __construct($id,$rand)
    {
        $this->id = $id;
        $this->rand  = $rand;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //   $cmd = "ffrecord -hide_banner -i rtmp://localhost:1935/hls/channel -max_muxing_queue_size 1024 recording.mkv";
      
        $ffmpeg = "ffrecord";
        $input  = 'public/recordings/' . $this->id . '/recording.mkv';
        $output = 'public/recordings/' . $this->id . '/recording.mp4';
        $progress = "public/recordings/progress.txt";
        
        $cmd = "$ffmpeg -hide_banner -y -i $input   -progress pipe:1 > $progress $output ";
        shell_exec($cmd);

        unlink($input);
        unlink("c:\/ffmpeg\/bin\/" . $this->rand . ".exe");

    }
}
