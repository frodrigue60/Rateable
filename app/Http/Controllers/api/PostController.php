<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Artist;
use App\Models\User;
use App\Models\Year;
use App\Models\Season;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {}

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
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {}

    public function search(Request $request)
    {
        $q = $request->input('q');

        $posts = Post::where('title', 'LIKE', '%' . $q . '%')->limit(5)->get(['title', 'slug']);

        $artists = Artist::where('name', 'LIKE', '%' . $q . '%')->limit(5)->get(['name', 'slug']);

        $users = User::where('name', 'LIKE', '%' . $q . '%')->limit(5)->get(['name', 'slug']);

        $data = [
            "posts" => $posts,
            "artists" => $artists,
            "users" => $users
        ];

        return response()->json($data);
    }

    public function animes(Request $request)
    {   
        //return response()->json(['request' => $request->all()]);
        $season = $request->season_id;
        $year = $request->year_id;
        $name = $request->name;

        $status = true;

        $posts = Post::where('status', $status)
            ->when($season, function ($query, $season) {
                $query->where('season_id', $season);
            })
            ->when($year, function ($query, $year) {
                $query->where('year_id', $year);
            })
            ->when($name, function ($query, $name) {
                $query->where('title', 'LIKE', '%' . $name . '%');
            })
            ->get();

        $posts = $posts->sortBy(function ($post) {
            return $post->title;
        });

        $posts = $this->paginate($posts, 24, $request->page)->withQueryString();
        $view = view('partials.posts.cards', compact('posts'))->render();

        return response()->json(['html' => $view, "lastPage" => $posts->lastPage()]);
    }

    public function paginate($collection, $perPage = 18, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $collection instanceof Collection ? $collection : Collection::make($collection);
        $collection = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $collection;
    }

    public function test(){
        return response()->json(['test' => true]);
    }
}
