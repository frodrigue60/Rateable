<?php

namespace App\Http\Controllers;

use App\Models\SongVariant;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class SongVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($song_id, $slug = null, $suffix = null, $version = null)
    {
        //dd($song_id, $suffix, $version);

        //$song = Song::with(['post', 'artists','videos'])->find($id);
        $song_variant = SongVariant::where('song_id','=', $song_id)->where('version','=',$version)->first();

        $song = $song_variant->song;
        
        $comments = Comment::with('user', 'likeCounter')
            ->where('rateable_id', '=', $song_id)
            ->where('comment', '!=', "")
            ->latest()
            ->limit(10)
            ->get();

        $comments_featured = Comment::with('user', 'likeCounter')
            ->where('rateable_id', '=', $song_id)
            ->where('comment', '!=', "")
            ->get()
            ->sortByDesc('likeCount')
            ->take(3);


        if (Auth::check() == true && $song_variant->song->averageRating == true) {

            switch (Auth::user()->score_format) {
                case 'POINT_100':
                    $score = round($song_variant->song->averageRating);
                    break;

                case 'POINT_10_DECIMAL':
                    $score = round($song_variant->song->averageRating / 10, 1);
                    break;

                case 'POINT_10':
                    $score = round($song_variant->song->averageRating / 10);
                    break;

                case 'POINT_5':
                    $score = round($song_variant->song->averageRating / 20);
                    break;

                default:
                    $score = round($song_variant->song->averageRating / 10);
                    break;
            }
        } else {
            $score = null;
        }

        //dd($song_variant,$score,$comments,$comments_featured);

        return view('public.songs.variants.show', compact('song','song_variant', 'score', 'comments', 'comments_featured'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
