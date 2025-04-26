<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('leaky_buckets', function (Blueprint $table) {
            $table->id();
            $table->integer('requests')->default(0);
            $table->integer('capacity');
            $table->float('leak_rate');
            $table->integer('time_window');
            $table->timestamp('last_checked');
            $table->timestamp('last_leak_reset')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('leaky_buckets');
    }
};
