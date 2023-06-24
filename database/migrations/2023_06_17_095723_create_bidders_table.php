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
        Schema::create('bidders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->bigInteger('item_id');
            $table->string('address'); // address of bidder
            $table->integer('points'); // total lodged points
            $table->integer('used')->default(0); // total used. 
            $table->tinyInteger('switch')->default(0);
            $table->boolean('winner')->default(0); 
            $table->string('hash', 1000)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidders');
    }
};
