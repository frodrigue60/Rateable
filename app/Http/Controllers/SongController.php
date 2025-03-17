<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Artist;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
    public function show($id, $slug = null, $suffix = null)
    {

        $song = Song::with(['post', 'artists','videos'])->find($id);


        if (Auth::check() == true && $song->averageRating == true) {

            switch (Auth::user()->score_format) {
                case 'POINT_100':
                    $score = round($song->averageRating);
                    break;

                case 'POINT_10_DECIMAL':
                    $score = round($song->averageRating / 10, 1);
                    break;

                case 'POINT_10':
                    $score = round($song->averageRating / 10);
                    break;

                case 'POINT_5':
                    $score = round($song->averageRating / 20);
                    break;

                default:
                    $score = round($song->averageRating / 10);
                    break;
            }
        } else {
            $score = null;
        }
        /* if (isset($song->artist->id)) {
            $artist = Artist::find($song->artist->id);
        } else {
            $artist = null;
        } */

        /* $this->count_views($song); */

        return view('public.songs.show', compact('song', 'score', 'comments'));
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
