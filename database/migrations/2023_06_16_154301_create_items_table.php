<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->text('description');
            $table->string('image', 1000);
            $table->boolean('image_type')->default(0); 
            
            $table->string('url', 1000)->nullable(); //token url lookup page
            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);

            $table->decimal('price', 64, 5)->default(0); //real price
            $table->decimal('bid_price', 64, 5)->default(0); //current bid price
            $table->decimal('start_price', 64, 5); //incremental price 

            $table->tinyInteger('status')->default(0); //1=started, 2=ended //3=claimed
            $table->tinyInteger('switch')->default(0);
            $table->integer('ctd_timer'); //countdown timer before bid starts
            $table->timestamp('start_time')->nullable(); //countdown timer before bid starts
            $table->timestamp('timer')->nullable(); //current timer

            $table->bigInteger('points')->default(0); //total lodged
            $table->bigInteger('start_points')->default(0); //total points acumulated before countdown
            $table->bigInteger('used')->default(0);  //points used
            $table->integer('free_bid')->default(0); //free bid aloted
            $table->integer('min_bid'); //min bid

            //bidder info
            $table->string('winner')->nullable(); //store the winners address
            $table->uuid('bidder_id')->nullable();
            //token info
            $table->decimal('prize', 64, 5)->default(0) ; //token amount
            $table->string('symbol')->nullable(); //symbol of normal token
            $table->tinyInteger('type'); //1=725 ,2=1155 , 3=erc20 token , 4=native token
            $table->string('contract_address')->nullable(); //=> contract address
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
