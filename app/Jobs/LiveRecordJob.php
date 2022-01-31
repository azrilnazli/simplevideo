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
    public $channel;
    public $rand;

    public function __construct($channel,$rand)
    {
        $this->channel = $channel;
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
        $input = " rtmp://localhost:1935/hls/" . $this->channel;
        $output = 'public/recordings/' . $this->channel . '.mkv';
        
        $cmd = "$ffmpeg -hide_banner -y -i $input -max_muxing_queue_size 1024 $output";
        shell_exec($cmd);

    }
}
