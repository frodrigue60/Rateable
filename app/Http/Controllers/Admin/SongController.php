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
    public function create($post_id)
    {
        $tags = Tag::all();
        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];
        $artists = Artist::all();
        $post = Post::find($post_id);
        $years = $this->SeasonsYears($tags)['years'];
        $seasons = $this->SeasonsYears($tags)['seasons'];

        return view('admin.songs.create', compact('artists', 'types', 'post', 'years', 'seasons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        $song = new Song();
        $song->song_romaji = $request->song_romaji;
        $song->song_jp = $request->song_jp;
        $song->song_en = $request->song_en;
        $song->post_id = $post_id;
        $song->type = $request->type;

        $tag = $request->season . ' ' . $request->year;
        /* 
        $song->ytlink = $request->ytlink;
        $song->scndlink = $request->scndlink;

        if ($request->hasFile('video')) {
            $validator = Validator::make($request->all(), [
                'video' => 'mimes:webm'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                $request->flash();
                return Redirect::back()
                    ->with('error', $errors);
            }
            $post = Post::find($request->post_id);
            $file_name = $post->slug . '-' . time() . '.' . 'webm';
            $song->video_src = $file_name;
            //Storage::disk('public')->put('/videos/',$file_name.$request->video);
            $request->video->storeAs('videos', $file_name, 'public');
        } else {
            $song->video_src = null;
        } */

        if ($request->theme_num >= 1) {
            $song->suffix = $song->type . $request->theme_num;
            $song->theme_num = $request->theme_num;
        } else {
            $song->suffix = null;
        }

        if ($song->save()) {
            $song->artists()->sync($request->artist_id);
            $song->tag($tag);
            return redirect(route('song.post.manage', $post_id))->with('success', 'song added successfully');
        } else {
            return redirect(route('admin.post.index'))->with('error', 'error');
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
        $years = $this->SeasonsYears($tags)['years'];
        $seasons = $this->SeasonsYears($tags)['seasons'];

        return view('admin.songs.edit', compact('song', 'artists', 'types', 'years', 'seasons'));
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
        //dd($request->all());
        $song = Song::find($id);
        $song->song_romaji = $this->decodeUnicodeIfNeeded($request->song_romaji);
        $song->song_jp = $request->song_jp;
        $song->song_en = $request->song_en;
        $song->post_id = $song->post->id;
        $song->type = $request->type;
        $song->theme_num = $request->theme_num;

        //dd($song->song->song_romaji);

        $tag = $request->season . ' ' . $request->year;

        if ($request->theme_num >= 1) {
            $song->suffix = $song->type . $request->theme_num;
            $song->theme_num = $request->theme_num;
        } else {
            $song->suffix = null;
        }

        if ($song->update()) {
            $song->artists()->sync($request->artist_id);
            $song->tag($tag);
            return redirect(route('song.post.manage', $song->post_id))->with('success', 'Song updated success');
        } else {
            return redirect(route('admin.post.index'))->with('error', 'error, something has been wrong');
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
    public function manage($id)
    {
        //dd(true);
        $post = Post::with('songs')->find($id);
        if ($post == null) {
            return redirect(route('admin.post.index'))->with('error', 'Item has been deleted!');
        }
        return view('admin.songs.manage', compact('post'));
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
}
