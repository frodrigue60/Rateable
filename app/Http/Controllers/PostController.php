<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Conner\Tagging\Model\Tag;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use willvincent\Rateable\Rateable;
use Illuminate\Support\Facades\Redirect;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types =['op','ed'];
        $tags = Tag::all();
        return view('admin.posts.create', compact('tags','types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->type = $request->type;
        $post->imagesrc = $request->imagesrc;
        $post->ytlink = $request->ytlink;
        //dd($request->all());
        $post->save();

        $tags = $request->tags;
        //$tags = explode(',', $request->tag);
        $post->tag($tags); // attach the tag


        return redirect(route('admin.post.index'))->with('status', 'Post updated Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        $tags = $post->tagged;
        return view('show', compact('post', 'tags'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $types =['op','ed'];
        $post = Post::find($id);
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'tags','types'));
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
        $post = Post::find($id);
        $post->title = $request->title;
        $post->type = $request->type;
        $post->imagesrc = $request->imagesrc;
        $post->ytlink = $request->ytlink;
        $post->save();

        $tags = $request->tags;
        //$tags = explode(',', $request->tag);
        $post->retag($tags); // delete current tags and save new tags

        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        return redirect(route('admin.post.index'))->with('status', 'Data deleted Successfully');
    }

    public function home()
    {
        if ($currentSeason = DB::table('current_season')->first() === null) {
            //dd('doesnt exist current season');
            $posts = Post::all()->where('type','op');

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('index', compact('posts', 'tags'));
        } else {
            $currentSeason = DB::table('current_season')->first();

            $posts = Post::withAllTags($currentSeason->name)->where('type','op')->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('index', compact('posts', 'tags'));
        }
    }

    public function endings()
    {
        if ($currentSeason = DB::table('current_season')->first() === null) {
            //dd('doesnt exist current season');
            $posts = Post::all()->where('type','ed');

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('index', compact('posts', 'tags'));
        } else {
            $currentSeason = DB::table('current_season')->first();

            $posts = Post::withAllTags($currentSeason->name)
            ->where('type','ed')
            ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('index', compact('posts', 'tags'));
        }
    }

    public function ratePost(Request $request, $id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $score = $request->score;
            
            if (blank($score)) {
                return redirect()->back()->with('status', 'Score has not been null');
            } else {
                if ($score <= 100) {
                    $post->rateOnce($score);
                    return redirect('/')->with('status', 'Post rated Successfully');
                } else {
                    return redirect('/')->with('status', 'Only values between 1 and 10');
                }
            }
            return redirect('/');
        } else {
            return redirect()->route('login');
        }
    }

    public function favorites()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $posts = Post::whereLikedBy($userId) // find only articles where user liked them
                ->with('likeCounter') // highly suggested to allow eager load
                ->get();
            //dd($likeds);

            return view('favorites', compact('posts'));
        } else {
            return redirect()->route('login');
        }
    }

    public function likePost($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $post = Post::find($id);
            $post->like($userId);
            
            return Redirect::back()->with('status', 'Post Like successfully!');
        }
        return redirect()->route('/')->with('status', 'Please login');
    }

    public function unlikePost($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $post = Post::find($id);
            
            $post->unlike($userId);
            
            return Redirect::back()->with('status', 'Post Like undo successfully!');
        }
        return redirect()->route('/')->with('status', 'Please login');
    }

    public function search(Request $request){
        $posts = Post::query()
            ->where('title', 'LIKE', "%{$request->input('search')}%")
            ->get();

        $slugged = $request->input('search');
        $tagsSearch = Post::withAnyTags($slugged)->get();

        return view('fromTags', compact('posts','tagsSearch'));
    }
}
