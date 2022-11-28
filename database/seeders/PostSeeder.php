<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\Post;
use Conner\Tagging\Taggable;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collection = [
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx11061-sIpBprNRfzCe.png',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx113717-fkqTxEqqga61.jpg',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx140960-vN39AmOWrVB5.jpg',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx9253-7pdcVzQSkKxT.jpg',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx129201-HJBauga2be8I.png',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx143270-iZOJX2DMUFMC.jpg',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx97986-pXb9GcQkPDcT.jpg',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx124845-sD8ZA0RGLRWT.jpg',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx101759-NhSwxv7HY9y9.jpg',
            'https://s4.anilist.co/file/anilistcdn/media/anime/cover/large/bx18671-RVIY9TGd737H.jpg'
        ];

        $randomtype = [
            'op',
            'ed'
        ];

        $tags = [
            'fall 2022',
            'winter 2023',
        ];

        $thumbnail = null;

        for ($i = 0; $i < 10; $i++) {
            /*DB::table('posts')->insert([
                'title' => Str::random(12),
                'type' => Arr::random($randomtype),
                'imagesrc' => Arr::random($collection),
                'ytlink' => 'https://www.youtube.com/embed/dlSbEP4V-gI',

            ]);*/

            $post = new Post;
            $post->title = Str::random(12);
            $post->type = Arr::random($randomtype);
            $post->imagesrc = Arr::random($collection);
            $post->ytlink = 'https://www.youtube.com/embed/dlSbEP4V-gI';
            $post->thumbnail = $thumbnail;
            
            $post->save();
            
            $post->tag($tags); // attach the tags
        }
    }
}
