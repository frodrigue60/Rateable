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
            $table->text('description')->nullable();
            $table->bigInteger('anilist_id')->nullable()->default(null);
            $table->string('thumbnail_src')->nullable()->default(null);
            $table->enum('status', ['stagged', 'published'])->default('stagged');
            $table->string('thumbnail')->nullable()->default(null);
            $table->string('banner')->nullable()->default(null);
            $table->string('banner_src')->nullable()->default(null);
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
