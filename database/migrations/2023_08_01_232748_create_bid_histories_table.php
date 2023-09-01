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
        Schema::create('bid_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('item_id');
            $table->uuid('user_id');
            $table->integer('amt')->default(0);
            $table->string('secrete'); 
            $table->string('time')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('trial')->default(0); 
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_histories');
    }
};
