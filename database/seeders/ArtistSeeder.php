<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $name = Str::random(5).' '.Str::random(5);
            $name_jp = Str::random(6);
            $slug = Str::slug($name);

            DB::table('artists')->insert([

                'name' => $name,
                'name_jp' => $name_jp,
                'slug' => $slug

            ]);
        }
    }
}
