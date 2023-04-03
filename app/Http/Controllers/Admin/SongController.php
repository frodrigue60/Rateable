<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Post;
use App\Models\Song;
use Illuminate\Http\Request;
use Conner\Tagging\Model\Tag;

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
    public function create($id)
    {
        $tags = Tag::all();
        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];
        $artists = Artist::all();
        $post = Post::find($id);
        return view('admin.songs.create',compact('id','artists','types','tags','post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->tags);
        $song = new Song();
        $song->song_romaji = $request->song_romaji;
        $song->song_jp = $request->song_jp;
        $song->song_en = $request->song_en;
        $song->artist_id = $request->artist_id;
        $song->post_id = $request->post_id;
        $song->type = $request->type;
        $song->ytlink = $request->ytlink;
        $song->scndlink = $request->scndlink;
        if ($request->theme_num >= 1) {
            $song->suffix = $song->type.$request->theme_num;
            $song->theme_num = $request->theme_num;
        } else {
            $song->suffix = null;
        }
        

        if ($song->save()) {
            $song->tag($request->tags);
            return redirect(route('admin.post.index'))->with('success','success');
        } else {
            return redirect(route('admin.post.index'))->with('error','error');
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
        return view('admin.songs.edit',compact('song','artists','types','tags'));
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
        $song = Song::find($id);
        $song->song_romaji = $request->song_romaji;
        $song->song_jp = $request->song_jp;
        $song->song_en = $request->song_en;
        $song->artist_id = $request->artist_id;
        $song->post_id = $request->post_id;
        $song->type = $request->type;
        $song->theme_num = $request->theme_num;
        $song->ytlink = $request->ytlink;
        $song->scndlink = $request->scndlink;
        if ($request->theme_num >= 1) {
            $song->suffix = $song->type.$request->theme_num;
            $song->theme_num = $request->theme_num;
        } else {
            $song->suffix = null;
        }

        if ($song->update()) {
            $song->tag($request->tags);
            return redirect(route('song.post.manage', $song->post_id))->with('success','Song updated success');
        } else {
            return redirect(route('admin.post.index'))->with('error','error, something has been wrong');
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
        //
    }
    public function manage($id){
        $post= Post::with('songs')->find($id);
        return view('admin.songs.manage',compact('post'));
    }
}
