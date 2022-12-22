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
            $table->string('slug');
            $table->unsignedBigInteger('song_id')->nullable();
            $table->unsignedBigInteger('artist_id')->nullable();
            $table->enum('type', ['op', 'ed'])->nullable();
            $table->text('ytlink')->nullable();
            $table->text('scndlink')->nullable();
            $table->string('thumbnail')->nullable();
            $table->bigInteger('view_count')->default(0);
            $table->timestamps();

            $table->foreign('artist_id')
            ->references('id')
            ->on('artists')
            ->onDelete('set null');
            

            $table->foreign('song_id')
            ->references('id')
            ->on('songs')
            ->onDelete('set null');
            
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
