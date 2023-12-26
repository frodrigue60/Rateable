<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use App\Models\SongVariant;

class SongVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $songVariant = SongVariant::find($id);

        return view("admin.songs.variants.videos.index", compact("songVariant"));
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
    public function store($song_id)
    {
        //dd($song_id, $request->all());
        $song = Song::find($song_id);

        $latestVersion = SongVariant::where('song_id', $song_id)
            ->max('version');

        $newVersion = $latestVersion !== null ? $latestVersion + 1 : 1;

        $songVariant = new SongVariant([
            'song_id' => $song_id,
            'version' => $newVersion,
        ]);

        //dd($songVariant);

        if ($songVariant->save()) {
            return redirect(route('song.post.manage', $song->post->id))->with('success', 'song variant added successfully');
        } else {
            return redirect(route('song.post.manage', $song->post->id))->with('error', 'error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $songVariant = SongVariant::find($id);

        dd($songVariant);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $songVariant = SongVariant::find($id);

        //dd($songVariant);

        return view('admin.songs.variants.edit', compact('songVariant'));
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
        $songVariant = SongVariant::find($id);
        $songVariant->version = $request->version;
        
        if ($songVariant->update()) {
            return redirect(route('song.post.manage', $songVariant->song->post_id))->with('success', 'Song updated success');
        } else {
            return redirect(route('song.post.manage', $songVariant->song->post_id))->with('error', 'Something has been wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $songVariant = SongVariant::find($id);

        if ($songVariant->delete()) {
            return redirect(route('song.post.manage', $songVariant->song->post->id))->with('success', 'song variant added successfully');
        } else {
            return redirect(route('song.post.manage', $songVariant->song->post->id))->with('error', 'error');
        }
    }
}
