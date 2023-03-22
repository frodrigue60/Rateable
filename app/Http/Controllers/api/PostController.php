<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use App\Models\Post;
use Conner\Tagging\Model\Tag;
use App\Models\Artist;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\User;

class PostController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return $post;
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        //dd($q);
        $posts = Post::where('title', 'LIKE', "%$q%")
        ->orWhere('suffix', 'LIKE', "%$q%")
        ->limit(5)
        ->get(['id', 'title', 'slug', 'type', 'theme_num', 'suffix']);

        $artists = Artist::where('name', 'LIKE', "%$q%")->limit(5)->get(['name', 'name_slug']);

        $tags = Tag::where('name', 'LIKE', "%$q%")->limit(5)->get(['name', 'slug']);

        $users = User::where('name', 'LIKE', "%$q%")->limit(5)->get(['id','name']);

        $data = ["posts" => $posts, "artists" => $artists, "tags" => $tags,"users" => $users];

        return response()->json($data);
    }
}
