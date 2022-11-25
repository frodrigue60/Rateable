<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $name = Str::random(6) . ' ' . Str::random(4);
            $slug = Str::slug($name);
            DB::table('tagging_tags')->insert([

                'name' => $name,
                'slug' => $slug,

            ]);
        }
    }
}
