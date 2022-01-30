<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class EncodeVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:encode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encode & Encrypt Video';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "Send 100 videos for encoding" . PHP_EOL;
        $i = 1;
        while($i < 10 ){
          echo "processing video $i" . PHP_EOL;
          sleep(2);
          $i++;  
        }
    }
}
