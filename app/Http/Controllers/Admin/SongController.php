<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Post;
use App\Models\Song;
use Illuminate\Http\Request;
use Conner\Tagging\Model\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Services\Breadcrumb;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($post_id)
    {
        $post = Post::find($post_id);
        $tags = Tag::all();
        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];
        $artists = Artist::all();
        

        return view('admin.songs.create', compact('artists', 'types', 'post', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        //dd($request->all());
        //$post = Post::find($post_id);
        $song = new Song();
        $song->song_romaji = $request->song_romaji;
        $song->song_jp = $request->song_jp;
        $song->song_en = $request->song_en;
        $song->post_id = $post_id;
        $song->type = $request->type;

        if ($request->theme_num != null) {
            $song->theme_num = $request->theme_num;
        } else {
            $song->theme_num = 1;
        }

        $song->slug = $song->type . $song->theme_num;

        //dd($song);
        if ($song->save()) {
            $song->artists()->sync($request->artists);
            $song->retag($request->tags);
            return redirect(route('posts.songs', $post_id))->with('success', 'song added successfully');
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
        $artists = Artist::all();
        $tags = Tag::all();
        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];

        return view('admin.songs.edit', compact('song', 'artists', 'types', 'tags'));
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
        
        $song->song_romaji = $this->decodeUnicodeIfNeeded($request->song_romaji);
        $song->song_jp = $request->song_jp;
        $song->song_en = $request->song_en;
        $song->post_id = $song->post->id;
        $song->type = $request->type;
        $song->theme_num = $request->theme_num;

        if ($request->theme_num != null) {
            $song->theme_num = $request->theme_num;
        } else {
            $song->theme_num = 1;
        }
        
        $song->slug = $song->type . $request->theme_num;

        //$song->slug = $post->slug . '/' . $song->type . $song->theme_num;

        if ($song->update()) {
            $song->artists()->sync($request->artists);
            $song->retag($request->tags);
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
            DB::table('ratings')
                ->where('rateable_id', '=', $id)->delete();
            DB::table('likeable_likes')
                ->where('likeable_id', '=', $id)->delete();
            DB::table('likeable_like_counters')
                ->where('likeable_id', '=', $id)->delete();

            /* Storage::disk('public')->delete('/videos/' . $old_video); */
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

    public function manage($post_id)
    {
        //dd(true);
        $post = Post::with('songs')->find($post_id);

        $breadcrumb = Breadcrumb::generate([
            [
                'name' => 'Index',
                'url' => route('admin.posts.index'),
            ],
            [
                'name' => $post->title,
                'url' => route('posts.songs', $post->id),
            ],
        ]);

        
        return view('admin.songs.manage', compact('post', 'breadcrumb'));
    }
}
