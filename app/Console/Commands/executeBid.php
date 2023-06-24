<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item; 
class executeBid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bid:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute bidding';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while(1){
            Item::execute_bid(); 
        }
        return; 
    }
}
