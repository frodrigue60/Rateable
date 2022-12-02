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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('song_romaji')->nullable();
            $table->string('song_jp')->nullable();
            $table->string('song_en')->nullable();
            $table->unsignedInteger('artist_id')->nullable();
            $table->enum('type', ['op', 'ed'])->nullable();
            $table->text('ytlink')->nullable();
            $table->text('scndlink')->nullable();
            $table->string('thumbnail')->nullable();

            $table->foreign('artist_id')
            ->references('id')
            ->on('artists')
            ->onDelete('set null');
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
        Schema::dropIfExists('posts');
    }
};
