<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Artisan;
use App\Models\Video;
use DB;
use Cache;
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


class LiveRecordJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $incoming_stream;
    public $rand;
    public $id;

    public function __construct($id,$incoming_stream,$rand)
    {
        $this->id  = $id;
        $this->incoming_stream = $incoming_stream;
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
      
        $ffmpeg =  $this->rand;
        $input =  $this->incoming_stream;
        $output = "public/recordings/$this->id/recording.mkv";
        $progress = "public/recordings/progress.txt";
        
        $cmd = "$ffmpeg -hide_banner -y -i $input -max_muxing_queue_size 1024 $output -progress pipe:1 > $progress";
        shell_exec($cmd);

    }
}
