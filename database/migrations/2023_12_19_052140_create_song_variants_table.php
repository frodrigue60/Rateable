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
            $table->unsignedBigInteger('version')->default('1');
            $table->unsignedBigInteger('song_id');
            $table->timestamps();
        });

        Schema::table('song_variants', function (Blueprint $table) {
            $table->foreign('song_id')->references('id')->on('songs')->onDelete('cascade');
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
