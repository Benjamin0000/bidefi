<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
class startBid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bid:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while(1){
            startBid();
        }
        return; 
    }
}
