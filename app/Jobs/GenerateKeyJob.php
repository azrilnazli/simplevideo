<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Artisan;
use App\Models\Video;
use DB;
use Cache;


class GenerateKeyJob extends Job
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
       // echo app()->basePath('public');
        $path =  app()->basePath('public') . "/videos/" . $this->video->id . '/keys';
        echo $this->video->name  . PHP_EOL; 
        $i = 1;

        $v = Video::where('id', $this->video->id )->first(); 

        do {

            ########################################
            // openssl rand 16  > enc$i.key
            file_put_contents($path . "/enc$i.key" , openssl_random_pseudo_bytes($length = 16 ));

            // echo http://localhost:8000/videos/$id/keys/enc$i.key >> tmp

            $content = "/videos/". $this->video->id  ."/keys/enc$i.key" . PHP_EOL;
            file_put_contents($path . "/tmp.txt" , $content);

            //openssl rand 16 
            file_put_contents($path . "/tmp.txt" , $path . "/enc$i.key" . PHP_EOL , FILE_APPEND);

            // mv tmp enc.keyinfo
            rename($path . "/tmp.txt", $path . "/enc.keyinfo");

             
            echo "$i - generating key for " . $this->video->name . PHP_EOL;
            echo "Work status is :  " . $this->video->work . PHP_EOL;

            ########################################
            sleep(5);
        
            Cache::forget('videos');
            $data = DB::table('videos')
                ->where('id', '=', $this->video->id)
                ->where('work', '=', 1)
                ->get();
            $i++;
        }
        while ($data->count());
    }
}
