<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Conner\Tagging\Model\Tag;
use App\Models\Artist;

class PostController extends Controller
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
    public function show($id)
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

    public function apiPosts(Request $request)
    {
        $q = $request->get('q');
        //dd($q);
        $posts = Post::where('title', 'LIKE', "%$q%")->orWhere('suffix', 'LIKE', "%$q%")->limit(5)->get(['id', 'title', 'slug','type','themeNum', 'suffix']);

        $artists = Artist::where('name', 'LIKE', "%$q%")->limit(5)->get(['name', 'name_slug']);

        $tags = Tag::where('name', 'LIKE', "%$q%")->limit(5)->get(['name', 'slug']);

        $data = ["posts" => $posts, "artists" => $artists, "tags" => $tags];

        return response()->json($data);
    }
}
