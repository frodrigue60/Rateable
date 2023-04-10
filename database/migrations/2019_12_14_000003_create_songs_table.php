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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('song_romaji')->nullable();
            $table->string('song_jp')->nullable();
            $table->string('song_en')->nullable();
            $table->unsignedBigInteger('artist_id')->nullable();
            $table->unsignedBigInteger('post_id');
            $table->string('theme_num')->nullable();
            $table->enum('type', ['OP', 'ED'])->nullable();
            $table->string('suffix')->nullable();
            $table->text('ytlink')->nullable();
            $table->text('scndlink')->nullable();
            
            $table->bigInteger('view_count')->default(0);
            $table->timestamps();
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
};
