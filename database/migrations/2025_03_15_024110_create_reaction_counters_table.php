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
        Schema::create('reaction_counters', function (Blueprint $table) {
            $table->id();
            $table->morphs('reactable'); // reactable_id y reactable_type
            $table->unsignedBigInteger('likes_count')->default(0); // Contador de likes
            $table->unsignedBigInteger('dislikes_count')->default(0); // Contador de dislikes
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
        Schema::dropIfExists('reaction_counters');
    }
};
