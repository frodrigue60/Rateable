<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_number')->default(1);
            $table->foreignId('song_id')->references('id')->on('songs')->onDelete('cascade');
            $table->unsignedBigInteger('views')->default(0);
            $table->string('slug')->unique();
            $table->foreignId('season_id')->references('id')->on('seasons')->onDelete('cascade');
            $table->foreignId('year_id')->references('id')->on('years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_variants');
    }
};
