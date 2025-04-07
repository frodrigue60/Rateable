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
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('anilist_id')->nullable();
            $table->boolean('status')->default(false);
            $table->string('thumbnail')->nullable();
            $table->string('thumbnail_src')->nullable();
            $table->string('banner')->nullable();
            $table->string('banner_src')->nullable();
            $table->foreignId('year_id')->constrained('years')->onDelete(null);
            $table->foreignId('season_id')->constrained('seasons')->onDelete(null);
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
