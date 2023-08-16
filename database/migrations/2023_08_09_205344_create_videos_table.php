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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->text('embed_code')->nullable()->default(null);
            $table->text('video_src')->nullable()->default(null);
            $table->enum('type', ['embed', 'file'])->default('embed');
            $table->unsignedBigInteger('song_id')->nullable();
            $table->timestamps();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->foreign('song_id')
                ->references('id')
                ->on('songs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
};
