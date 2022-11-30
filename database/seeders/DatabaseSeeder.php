<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]); 
        $password = 'a12edc21cd';
        $post = new User;
        $post->name = 'Luis Rodz';
        $post->email = 'frodrigue60@gmail.com';
        $post->password = bcrypt($password);
        $post->type = 'admin';
        $post->save();
    }
}
