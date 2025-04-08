<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Season;
use App\Models\Song;
use Illuminate\Http\Request;
use App\Models\SongVariant;
use App\Models\Year;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use App\Services\Breadcrumb;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

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
        $song = new Song();
        $song->song_romaji = Str::of($request->song_romaji)->trim();
        $song->song_jp = Str::of($request->song_jp)->trim();
        $song->song_en = Str::of($request->song_en)->trim();
        $song->post_id =  $request->post_id;
        $song->season_id = $request->season_id;
        $song->year_id = $request->year_id;
        $song->type = $request->type;

        $artistsNames = (explode(',', $request->artists));

        $artistsIds = [];

        foreach ($artistsNames as $name) {
            $name = preg_replace('/\s+/', ' ', $name);
            $artist = Artist::firstOrCreate(
                [
                    'slug' => Str::slug($name),
                ],
                [
                    'name' =>  $name,
                ]
            );
            $artistsIds[] = $artist->id;
        }

        $latestVersion = Song::where('post_id', $request->post_id)
            ->where('type', $request->type)
            ->max('theme_num');

        $newVersion = $latestVersion !== null ? $latestVersion + 1 : 1;

        $song->theme_num = $newVersion;

        $song->slug = $song->type . $song->theme_num;

        if ($song->save()) {
            $song->artists()->sync($artistsIds);
            return redirect(route('posts.songs', $request->post_id))->with('success', 'song added successfully');
        } else {
            return redirect(route('admin.posts.index'))->with('error', 'error');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $song = Song::find($id);
        /* $artists = Artist::all(); */
        $seasons = Season::all();
        $years = Year::all();
        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED'],
            ['name' => 'Insert', 'value' => 'INS'],
            ['name' => 'Other', 'value' => 'OTH']
        ];

        return view('admin.songs.edit', compact('song', /* 'artists', */ 'types', 'seasons', 'years'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $song_id)
    {
        //dd($request->all());
        $song = Song::find($song_id);

        $song->song_romaji = Str::of($request->song_romaji)->trim();
        $song->song_jp = Str::of($request->song_jp)->trim();
        $song->song_en = Str::of($request->song_en)->trim();
        $song->post_id = $song->post->id;
        $song->season_id = $request->season_id;
        $song->year_id = $request->year_id;
        $song->type = $request->type;
        $song->theme_num = $request->theme_num;

        $artistsNames = (explode(',', $request->artists));

        $artistsIds = [];

        foreach ($artistsNames as $name) {
            $name = preg_replace('/\s+/', ' ', $name);
            $artist = Artist::firstOrCreate(
                [
                    'slug' => Str::slug($name),
                ],
                [
                    'name' =>  $name,
                ]
            );
            $artistsIds[] = $artist->id;
        }

        if ($request->theme_num != null) {
            $song->theme_num = $request->theme_num;
        } else {
            $song->theme_num = 1;
        }

        $song->slug = $song->type . $song->theme_num;

        if ($song->update()) {
            $song->artists()->sync($artistsIds);
            return redirect(route('posts.songs', $song->post_id))->with('success', 'Song updated success');
        } else {
            return redirect(route('admin.posts.index'))->with('error', 'error, something has been wrong');
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
        $song = Song::find($id);
        $song->artists()->detach();
        if ($song->delete()) {

            return redirect()->back()->with('success', 'Song ' . $song->id . ' has been deleted');
        } else {
            return redirect()->back()->with('error', 'A error has been ocurred');
        }
    }

    public function SeasonsYears($tags)
    {
        $tagNames = [];
        $tagYears = [];

        for ($i = 0; $i < count($tags); $i++) {
            [$name, $year] = explode(' ', $tags[$i]->name);

            if (!in_array($year, $tagNames)) {
                $years[] = ['name' => $year, 'value' => $year];
                $tagNames[] = $year; // Agregamos el año al array de nombres para evitar duplicados
            }

            if (!in_array($name, $tagYears)) {
                $seasons[] = ['name' => $name, 'value' => $name];
                $tagYears[] = $name; // Agregamos el año al array de nombres para evitar duplicados
            }
        }

        $data = [
            'years' => $years,
            'seasons' => $seasons
        ];
        return $data;
    }

    public function decodeUnicodeIfNeeded($string)
    {
        // Validar si la cadena contiene secuencias Unicode (\uXXXX)
        if (preg_match('/\\\u[0-9a-fA-F]{4}/', $string)) {
            // Decodificar secuencias Unicode.
            return json_decode('"' . $string . '"');
        }
        return $string;
    }

    public function addVariant($song_id)
    {
        //dd($song_id, $request->all());
        $song = Song::find($song_id);

        $latestVersion = SongVariant::where('song_id', $song_id)
            ->max('version_number');

        $newVersion = $latestVersion !== null ? $latestVersion + 1 : 1;

        $slug = 'v' . $newVersion;

        $songVariant = new SongVariant();
        $songVariant->song_id = $song_id;
        $songVariant->version_number = $newVersion;
        $songVariant->slug = $slug;
        $songVariant->season_id = $song->season_id;
        $songVariant->year_id = $song->year_id;

        //dd($songVariant);

        if ($songVariant->save()) {
            return redirect(route('admin.songs.variants', $songVariant->song->id))->with('success', 'Song updated success');
        } else {
            return redirect(route('admin.songs.variants', $songVariant->song->id))->with('error', 'Something has been wrong');
        }
    }

    public function variants($song_id)
    {
        $song = Song::find($song_id);
        $post = $song->post;
        $song_variants = $song->songVariants;

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
        ]);
        //dd($song_variants); 
        return view('admin.variants.manage', compact('song_variants', 'song', 'breadcrumb'));
    }
}
