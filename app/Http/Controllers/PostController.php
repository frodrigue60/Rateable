<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Song;
use Conner\Tagging\Model\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->paginate(10);
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
        $artists = Artist::all();
        return view('admin.posts.create', compact('tags', 'types', 'artists'));
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
            $post->slug = Str::slug($request->title);

            if ($request->artist_id != true) {
                $post->artist_id = null;
            } else {
                $post->artist_id = $request->artist_id;
            }

            $post->type = $request->type;
            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;

            $file_extension = $request->file->extension();
            //$file_mime_type = $request->file->getClientMimeType();

            $file_name = 'thumbnail_' . time() . '.' . $file_extension;

            $post->thumbnail = $file_name;

            $request->file->storeAs('thumbnails', $file_name, 'public');
            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();

            $post->song_id = $song->id;

            $post->save();

            $tags = $request->tags;
            $post->tag($tags);

            return redirect(route('admin.post.index'))->with('status', 'Post created Successfully, has file');
        } else {
            //dd($request->all());
            $post = new Post;
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
            $post->type = $request->type;
            if ($request->artist_id != true) {
                $post->artist_id = null;
            } else {
                $post->artist_id = $request->artist_id;
            }

            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;

            if ($request->imagesrc == null) {
                return Redirect::back()->with('status', 'Post not created, images not founds');
            }

            $image_file_data = file_get_contents($request->imagesrc);
            $ext = pathinfo($request->imagesrc, PATHINFO_EXTENSION);
            $file_name = 'thumbnail_' . time() . '.' . $ext;
            Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
            $post->thumbnail = $file_name;


            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();

            $post->song_id = $song->id;

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
        if (Auth::check() && Auth::user()->type == 'admin') {

            $score_format = Auth::user()->score_format;

            $post = Post::findOrFail($id);
            $artist = $post->artist;
            $tags = $post->tagged;
            //dd($post);
            return view('show', compact('post', 'tags', 'score_format', 'artist'));
        }
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            $post = Post::findOrFail($id);
            $tags = $post->tagged;
            $artist = $post->artist;
            return view('show', compact('post', 'tags', 'score_format', 'artist'));
        } else {
            $post = Post::findOrFail($id);
            $tags = $post->tagged;
            $artist = $post->artist;

            return view('show', compact('post', 'tags', 'artist'));
        }
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
        $song = Song::find($post->song_id);
        $tags = Tag::all();
        $artists = Artist::all();

        return view('admin.posts.edit', compact('post', 'tags', 'types', 'artists', 'song'));
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
            $post->slug = Str::slug($request->title);

            if ($request->artist_id != true) {
                $post->artist_id = null;
            } else {
                $post->artist_id = $request->artist_id;
            }

            $post->type = $request->type;

            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;

            $file_extension = $request->file->extension();
            //$file_mime_type = $request->file->getClientMimeType();


            Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);

            $file_name = 'thumbnail_' . time() . '.' . $file_extension;

            $post->thumbnail = $file_name;

            $request->file->storeAs('thumbnails', $file_name, 'public');
            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();
            $post->song_id = $song->id;
            $post->save();

            $tags = $request->tags;
            $post->tag($tags);
            return redirect(route('admin.post.index'))->with('status', 'Post updated Successfully, has file image');
        } else {
            $post = Post::find($id);
            $old_thumbnail = $post->thumbnail;

            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
            if ($request->artist_id != true) {
                $post->artist_id = null;
            } else {
                $post->artist_id = $request->artist_id;
            }

            $post->type = $request->type;

            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;
            if ($request->imagesrc == null) {
                return redirect(route('admin.post.index'))->with('status', 'Post not created, images not founds');
            }
            Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);
            $image_file_data = file_get_contents($request->imagesrc);
            $ext = pathinfo($request->imagesrc, PATHINFO_EXTENSION);
            $file_name = 'thumbnail_' . time() . '.' . $ext;
            Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
            $post->thumbnail = $file_name;
            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();

            $post->song_id = $song->id;
            $post->save();
            $tags = $request->tags;
            $post->retag($tags);
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

    public function openings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $currentSeason = DB::table('current_season')->first();

        if ($currentSeason == null) {

            $posts = Post::where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('seasonal', compact('posts', 'tags', 'score_format'));
        } else {
            $currentSeason = DB::table('current_season')->first();

            $posts = Post::withAllTags($currentSeason->name)
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('seasonal', compact('posts', 'tags', 'score_format'));
        }
    }
    public function endings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $currentSeason = DB::table('current_season')->first();

        if ($currentSeason == null) {

            $posts = Post::where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('seasonal', compact('posts', 'tags', 'score_format'));
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

            return view('seasonal', compact('posts', 'tags', 'score_format'));
        }
    }

    public function ratePost(Request $request, $id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $score = $request->score;
            $score_format = $request->score_format;

            if (blank($score)) {
                return redirect()->back()->with('status', 'Score can not be null');
            }
            switch ($score_format) {
                case 'POINT_100':
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 100)) {
                        $post->rateOnce($score);
                        return redirect()->back()->with('status', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('status', 'Only values between 1 and 100');
                    }
                    break;

                case 'POINT_10_DECIMAL':
                    settype($score, "float");
                    if (($score >= 1) && ($score <= 10)) {
                        $int = intval($score * 10);
                        $post->rateOnce($int);
                        return redirect()->back()->with('status', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('status', 'Only values between 1 and 10 (can use decimals)');
                    }
                    break;
                case 'POINT_10':
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 10)) {
                        $int = intval($score * 10);
                        $post->rateOnce($int);
                        return redirect()->back()->with('status', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('status', 'Only values between 1 and 10 (only integer numbers)');
                    }
                    break;
                case 'POINT_5':
                    settype($score, "integer");

                    if (($score >= 1) && ($score <= 100)) {
                        if ($score <= 20) {
                            $score = 20;
                        }
                        if (($score > 20) && ($score <= 40)) {
                            $score = 40;
                        }
                        if (($score > 40) && ($score <= 60)) {
                            $score = 60;
                        }
                        if (($score > 60) && ($score <= 80)) {
                            $score = 80;
                        }
                        if ($score > 80) {
                            $score = 100;
                        }
                        $post->rateOnce($score);
                        return redirect()->back()->with('status', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('status', 'Only values between 1 and 100');
                    }
                    break;


                default:
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 100)) {
                        $post->rateOnce($score);
                        return redirect()->back()->with('status', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('status', 'Only values between 1 and 100');
                    }
                    break;
            }
        }
        return redirect()->route('login');
    }

    public function favorites()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
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

            return view('favorites', compact('openings', 'endings', 'score_format'));
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
    public function filter()
    {
        $posts = Post::paginate(14);
        
        return view('filter',compact('posts'));
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

    public function seasonalranking()
    {
        $currentSeason = DB::table('current_season')->first();
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        if ($currentSeason == null) {
            $op_count = Post::where('type', 'op')->count();
            $ed_count = Post::where('type', 'ed')->count();

            $openings = Post::where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $endings = Post::where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            return view('ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'score_format'));
        } else {
            //search the current season and the posts
            $currentSeason = DB::table('current_season')->first();

            $openings = Post::withAllTags($currentSeason->name)
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();
            $op_count = $openings->count();

            $endings = Post::withAllTags($currentSeason->name)
                ->where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();
            $ed_count = $endings->count();


            //dd($currentSeason, $op_count, $ed_count, $openings, $endings);

            return view('ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'currentSeason', 'score_format'));
        }
    }
    public function globalrank()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $getOpenings = Post::where('type', 'op')
            ->orderBy('title', 'asc')
            ->get();
        $op_count = $getOpenings->count();

        $openings = $getOpenings->sortByDesc('averageRating')->take(100);

        $getEndings = Post::where('type', 'ed')
            ->orderBy('title', 'asc')
            ->get();
        $ed_count = $getEndings->count();

        $endings = $getEndings->sortByDesc('averageRating')->take(100);

        return view('ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'score_format'));
    }

    public function showBySlug($id, $slug)
    {
        if (Auth::check() && Auth::user()->type == 'admin') {
            $score_format = Auth::user()->score_format;

            $post = Post::where('id', '=', $id)->first();
            //dd($post);
            $artist = $post->artist;
            $tags = $post->tagged;
            //dd($post);
            return view('show', compact('post', 'tags', 'score_format', 'artist'));
        }
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            $post = Post::where('id', '=', $id)->first();
            $tags = $post->tagged;
            $artist = $post->artist;
            $this->count_views($id);
            return view('show', compact('post', 'tags', 'score_format', 'artist'));
        } else {
            $post = Post::where('id', '=', $id)->first();
            $tags = $post->tagged;
            $artist = $post->artist;
            $this->count_views($id);

            return view('show', compact('post', 'tags', 'artist'));
        }
    }
    public function count_views($id)
    {
        if (!Session::has('page_visited_' . $id)) {
            DB::table('posts')
                ->where('id', $id)
                ->increment('view_count');
            Session::put('page_visited_' . $id, true);
        }
    }
    public function apiPosts(Request $request)
    {
        $q = $request->get('q');
        //dd($q);
        $posts = Post::where('title', 'LIKE', "%$q%")->limit(5)->get(['id', 'title', 'slug']);

        $artists = Artist::where('name', 'LIKE', "%$q%")->limit(5)->get(['name', 'name_slug']);

        $tags = Tag::where('name', 'LIKE', "%$q%")->limit(5)->get(['name', 'slug']);

        $data = ["posts" => $posts, "artists" => $artists, "tags"=>$tags];

        return response()->json($data);
    }
}
