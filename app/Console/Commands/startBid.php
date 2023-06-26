<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Item; 

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
    protected $description = 'start the bidding';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while(1){
            Item::start_bid();
        }
        return; 
    }
}
