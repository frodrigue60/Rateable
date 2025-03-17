<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use App\Models\SongVariant;
use App\Models\Video;
use App\Services\Breadcrumb;

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
        //dd($song_id, $request->all());
        $song = Song::find($request->song_id);

        $latestVersion = SongVariant::where('song_id', $song->id)
            ->max('version_number');

        $newVersion = $latestVersion !== null ? $latestVersion + 1 : 1;

        $slug ='v' . $newVersion;

        $songVariant = new SongVariant();
        $songVariant->song_id = $song->id;
        $songVariant->version_number = $newVersion;
        $songVariant->slug = $slug;
        $songVariant->season_id = $song->season_id;
        $songVariant->year_id = $song->year_id;

        //dd($songVariant);

        if ($songVariant->save()) {
            return redirect(route('admin.songs.variants', $song->id))->with('success', 'song variant added successfully');
        } else {
            return redirect(route('posts.songs', $song->post->id))->with('error', 'error');
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
        $song = $songVariant->song;
        $post = $song->post;

        $breadcrumb = Breadcrumb::generate([
            [
                'name' => 'Index',
                'url' => route('admin.posts.index'),
            ],
            [
                'name' => $post->title,
                'url' => route('posts.songs', $post->id),
            ],
            [
                'name' => $song->slug,
                'url' => route('admin.songs.variants', $song->id),
            ],
            [
                'name' => $songVariant->slug,
                'url' => '',
            ],
        ]);

        return view('admin.variants.edit', compact('songVariant', 'breadcrumb'));
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
        $song = $songVariant->song;

        $latestVersion = SongVariant::where('song_id', $song->id)
            ->max('version_number');

        $newVersion = $latestVersion !== null ? $latestVersion + 1 : 1;

        $slug ='v' . $newVersion;

        $songVariant->song_id = $song->id;
        $songVariant->version_number = $newVersion;
        $songVariant->slug = $slug;
        $songVariant->season_id = $song->season_id;
        $songVariant->year_id = $song->year_id;

        if ($songVariant->update()) {
            return redirect(route('admin.songs.variants', $songVariant->song->id))->with('success', 'Song updated success');
        } else {
            return redirect(route('admin.songs.variants', $songVariant->song->id))->with('error', 'Something has been wrong');
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
            return redirect(route('admin.songs.variants', $songVariant->song->id))->with('success', 'song variant added successfully');
        } else {
            return redirect(route('admin.songs.variants', $songVariant->song->id))->with('error', 'error');
        }
    }

    public function videos($variant_id)
    {
        $song_variant = SongVariant::find($variant_id);
        //dd($song_variant);
        $song = $song_variant->song;
        $post = $song->post;
        $video = $song_variant->video;

        $breadcrumb = Breadcrumb::generate([
            [
                'name' => 'Index',
                'url' => route('admin.posts.index'),
            ],
            [
                'name' => $post->title,
                'url' => route('posts.songs', $post->id),
            ],
            [
                'name' => $song->slug,
                'url' => route('admin.songs.variants', $song->id),
            ],
            [
                'name' => $video->id,
                'url' => route('admin.songs.variants', $song->id),
            ],
        ]);

        return view("admin.videos.index", compact("song_variant", 'breadcrumb'));
    }

    public function addVideos($variant_id)
    {

        $song_variant = SongVariant::find($variant_id);
        $song = $song_variant->song;
        $post = $song->post;

        $breadcrumb = Breadcrumb::generate([
            [
                'name' => 'Index',
                'url' => route('admin.posts.index'),
            ],
            [
                'name' => $post->title,
                'url' => route('posts.songs', $post->id),
            ],
            [
                'name' => $song->slug,
                'url' => route('admin.songs.variants', $song->id),
            ],
            [
                'name' => $song_variant->slug . ' - '. 'video',
                'url' => '',
            ],
        ]);

        return view('admin.videos.create', compact('song', 'song_variant', 'breadcrumb'));
    }
}
