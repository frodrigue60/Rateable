<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Conner\Tagging\Model\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = ['op', 'ed'];
        $tags = Tag::all();
        return view('admin.posts.create', compact('tags', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $validator = Validator::make($request->all(), [
                'file' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return Redirect::back()->with('status', $errors);
            }

            $post = new Post;
            $post->title = $request->title;
            $post->type = $request->type;

            $post->ytlink = $request->ytlink;

            $file_extension = $request->file->extension();
            //$file_mime_type = $request->file->getClientMimeType();

            $file_name = 'thumbnail_' . time() . '.' . $file_extension;

            $post->thumbnail = $file_name;

            $request->file->storeAs('thumbnails', $file_name, 'public');

            $post->save();

            $tags = $request->tags;
            $post->tag($tags);

            return redirect(route('admin.post.index'))->with('status', 'Post created Successfully, has file');
        } else {

            $post = new Post;
            $post->title = $request->title;
            $post->type = $request->type;

            $post->ytlink = $request->ytlink;

            if ($request->imagesrc == null) {
                return Redirect::back()->with('status', 'Post not created, images not founds');
            }
            $image_file_data = file_get_contents($request->imagesrc);
            $ext = pathinfo($request->imagesrc, PATHINFO_EXTENSION);
            $file_name = 'thumbnail_' . time() . '.' . $ext;
            Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
            $post->thumbnail = $file_name;
            $post->save();
            $tags = $request->tags;
            $post->tag($tags);
            return redirect(route('admin.post.index'))->with('status', 'Post created Successfully, has url image');
        }
        return redirect(route('admin.post.index'))->with('status', 'Post not created, image not found');
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

        //$userid = Auth::user()->id;

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
        $types = ['op', 'ed'];
        $post = Post::find($id);
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'tags', 'types'));
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
        if ($request->hasFile('file')) {
            $validator = Validator::make($request->all(), [
                'file' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return Redirect::back()->with('status', $errors);
            }

            $post = Post::find($id);
            $old_thumbnail = $post->thumbnail;

            $post->title = $request->title;
            $post->type = $request->type;

            $post->ytlink = $request->ytlink;

            $file_extension = $request->file->extension();
            //$file_mime_type = $request->file->getClientMimeType();


            Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);

            $file_name = 'thumbnail_' . time() . '.' . $file_extension;

            $post->thumbnail = $file_name;

            $request->file->storeAs('thumbnails', $file_name, 'public');

            $post->save();

            $tags = $request->tags;
            $post->tag($tags);
            return redirect(route('admin.post.index'))->with('status', 'Post updated Successfully, has file image');
        } else {
            $post = Post::find($id);
            $old_thumbnail = $post->thumbnail;

            $post->title = $request->title;
            $post->type = $request->type;

            $post->ytlink = $request->ytlink;
            if ($request->imagesrc == null) {
                return redirect(route('admin.post.index'))->with('status', 'Post not created, images not founds');
            }
            Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);
            $image_file_data = file_get_contents($request->imagesrc);
            $ext = pathinfo($request->imagesrc, PATHINFO_EXTENSION);
            $file_name = 'thumbnail_' . time() . '.' . $ext;
            Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
            $post->thumbnail = $file_name;
            $post->save();
            $tags = $request->tags;
            $post->tag($tags);
            return redirect(route('admin.post.index'))->with('status', 'Post created Successfully, has url image');
        }
        return redirect(route('admin.post.index'))->with('status', 'Post not created, image not found');
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
        $file = $post->thumbnail;

        Storage::disk('public')->delete('/thumbnails/' . $file);
        $post->delete();

        return Redirect::back()->with('status', 'Post Deleted successfully!');
    }

    //return index view with all openings
    public function home()
    {
        if (Auth::check()) {
            $currentSeason = DB::table('current_season')->first();
            $score_format = Auth::user()->score_format;

            if ($currentSeason  == null) {

                $posts = Post::where('type', 'op')
                    ->orderBy('title', 'asc')
                    ->get();
                //->where('type', 'op')
                //->orderBy('title', 'asc');

                $tags = DB::table('tagging_tags')
                    ->orderBy('name', 'desc')
                    ->take(5)
                    ->get();

                return view('index', compact('posts', 'tags', 'score_format'));
            } else {
                //search the current season and the posts
                $currentSeason = DB::table('current_season')->first();

                $posts = Post::withAllTags($currentSeason->name)
                    ->where('type', 'op')
                    ->orderBy('title', 'asc')
                    ->get();

                $tags = DB::table('tagging_tags')
                    ->orderBy('name', 'desc')
                    ->take(5)
                    ->get();

                return view('index', compact('posts', 'tags', 'score_format'));
            }
        }
        //if exist current season setted
        $currentSeason = DB::table('current_season')->first();
        

        if ($currentSeason  == null) {

            $posts = Post::where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();
            //->where('type', 'op')
            //->orderBy('title', 'asc');

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('index', compact('posts', 'tags'));
        } else {
            //search the current season and the posts
            $currentSeason = DB::table('current_season')->first();

            $posts = Post::withAllTags($currentSeason->name)
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('index', compact('posts', 'tags'));
        }
    }

    public function endings()
    {
        $currentSeason = DB::table('current_season')->first();
        if ($currentSeason == null) {

            $posts = Post::where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('index', compact('posts', 'tags'));
        } else {
            $currentSeason = DB::table('current_season')->first();

            $posts = Post::withAllTags($currentSeason->name)
                ->where('type', 'ed')
                ->orderBy('title', 'asc')
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
                return redirect()->back()->with('status', 'Score can not be null');
            }

            if (($score >= 1) && ($score <= 100)) {
                $post->rateOnce($score);
                return redirect()->back()->with('status', 'Post rated Successfully');
            } else {
                return redirect()->back()->with('status', 'Only values between 1 and 100');
            }
        }
        return redirect()->route('login');
    }

    public function favorites()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $openings = Post::whereLikedBy($userId) // find only articles where user liked them
                ->with('likeCounter') // highly suggested to allow eager load
                ->where('type', 'op')
                ->get();

            $endings = Post::whereLikedBy($userId) // find only articles where user liked them
                ->with('likeCounter') // highly suggested to allow eager load
                ->where('type', 'ed')
                ->get();

            //dd($openings,$endings);

            return view('favorites', compact('openings', 'endings'));
        } else {
            return redirect()->route('login');
        }
    }

    public function likePost($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Post::find($id)->like($userId);

            return Redirect::back()->with('status', 'Post Like successfully!');
        }
        return redirect()->route('/')->with('status', 'Please login');
    }

    public function unlikePost($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Post::find($id)->unlike($userId);

            return Redirect::back()->with('status', 'Post Like undo successfully!');
        }
        return redirect()->route('/')->with('status', 'Please login');
    }

    //public seasrch posts
    public function search(Request $request)
    {
        if ($request->input('search') != null) {
            $openings = Post::query()
                ->where('title', 'LIKE', "%{$request->input('search')}%")
                ->where('type', '=', 'op')
                ->get();

            $endings = Post::query()
                ->where('title', 'LIKE', "%{$request->input('search')}%")
                ->where('type', '=', 'ed')
                ->get();

            return view('fromTags', compact('openings', 'endings'));
        }
        return redirect()->route('/')->with('status', 'Search a value');
    }

    //seach posts in admin pannel
    public function searchPost(Request $request)
    {
        if (Auth::check() && Auth::user()->type == 'admin') {
            $posts = Post::query()
                ->where('title', 'LIKE', "%{$request->input('search')}%")
                ->paginate(10);

            return view('admin.posts.index', compact('posts'));
        } else {
            return redirect()->route('/')->with('status', 'Only admins');
        }
    }

    public function ranking()
    {
        //if current season doesnt exist
        $currentSeason = DB::table('current_season')->first();
        //dd($currentSeason);
        if ($currentSeason == null) {
            $op_count = Post::where('type', 'op')->count();
            $ed_count = Post::where('type', 'ed')->count();

            $openings = Post::where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $endings = Post::where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            return view('ranking', compact('openings', 'endings', 'op_count', 'ed_count'));
        } else {
            //search the current season and the posts
            $currentSeason = DB::table('current_season')->first();

            $op_count = Post::withAllTags($currentSeason->name)
                ->where('type', 'op')
                ->count();

            $ed_count = Post::withAllTags($currentSeason->name)
                ->where('type', 'ed')
                ->count();

            $openings = Post::withAllTags($currentSeason->name)
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $endings = Post::withAllTags($currentSeason->name)
                ->where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            //dd($currentSeason, $op_count, $ed_count, $openings, $endings);

            return view('ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'currentSeason'));
        }
    }
}
