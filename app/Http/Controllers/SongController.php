<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Auth;
use App\Models\Year;
use App\Models\Season;
use App\Models\Post;

class SongController extends Controller
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
    public function show($anime_slug, $song_slug)
    {
        $post = Post::with(['songs'])->where('slug', $anime_slug)->first();

        $song = Song::with(['songVariants.video'])
            ->where('slug', $song_slug)
            ->where('post_id', $post->id)
            ->first();

        //dd([$post, $song]);

        return view('public.songs.show', compact('song','post'));
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

    public function seasonal()
    {
        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();

        return view('public.songs.seasonal', compact('currentSeason', 'currentYear'/* , 'openings', 'endings' */));
    }
}
