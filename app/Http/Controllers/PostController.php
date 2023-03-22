<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Song;
use App\Models\User;
use Conner\Tagging\Model\Tag;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use stdClass;
use Intervention\Image\ImageManagerStatic as Image;


use function PHPSTORM_META\type;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recently = Post::where('status', '=', 'published')->get()->sortByDesc('created_at')->take(20);
        $popular = Post::where('status', '=', 'published')->get()->sortByDesc('likeCount')->take(15);
        $viewed = Post::where('status', '=', 'published')->get()->sortByDesc('viewCount')->take(15);

        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $allOpenings = Post::where('status', 'published')
            ->where('type', 'op')
            ->get();
        $allEndings = Post::where('status', 'published')
            ->where('type', 'ed')
            ->get();
        $openings = $allOpenings->sortByDesc('averageRating')->take(10);
        $endings = $allEndings->sortByDesc('averageRating')->take(10);

        return view('index', compact('openings', 'endings', 'recently', 'popular', 'viewed', 'score_format'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
    public function show($id, $slug)
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            $post = Post::findOrFail($id);
            $tags = $post->tagged;
            $artist = $post->artist;
            $this->count_views($post);
            return view('public.posts.show', compact('post', 'tags', 'score_format', 'artist'));
        } else {
            $post = Post::findOrFail($id);
            $tags = $post->tagged;
            $artist = $post->artist;
            $this->count_views($post);

            return view('public.posts.show', compact('post', 'tags', 'artist'));
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

    public function openings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $currentSeason = DB::table('tagging_tags')->where('flag', '1')->first();

        if ($currentSeason == null) {

            $posts = Post::where('status', '=', 'published')
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('public.posts.seasonal', compact('posts', 'tags', 'score_format'));
        } else {

            $posts = Post::withAnyTag($currentSeason->name)
                ->where('status', '=', 'published')
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('public.posts.seasonal', compact('posts', 'tags', 'score_format', 'currentSeason'));
        }
    }
    public function endings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $currentSeason = DB::table('tagging_tags')->where('flag', '1')->first();

        if ($currentSeason == null) {

            $posts = Post::where('status', '=', 'published')
                ->where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('public.posts.seasonal', compact('posts', 'tags', 'score_format'));
        } else {

            $posts = Post::withAnyTag($currentSeason->name)
                ->where('status', '=', 'published')
                ->where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('public.posts.seasonal', compact('posts', 'tags', 'score_format', 'currentSeason'));
        }
    }

    public function ratePost(Request $request, $id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $score = $request->score;
            $score_format = $request->score_format;

            if (blank($score)) {
                return redirect()->back()->with('warning', 'Score can not be null');
            }
            switch ($score_format) {
                case 'POINT_100':
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 100)) {
                        $post->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;

                case 'POINT_10_DECIMAL':
                    settype($score, "float");
                    if (($score >= 1) && ($score <= 10)) {
                        $int = intval($score * 10);
                        $post->rateOnce($int);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (can use decimals)');
                    }
                    break;
                case 'POINT_10':
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 10)) {
                        $int = intval($score * 10);
                        $post->rateOnce($int);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (only integer numbers)');
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
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;


                default:
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 100)) {
                        $post->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;
            }
        }
        return redirect()->route('login');
    }

    public function favorites(Request $request)
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            $user = Auth::user();
        } else {
            return redirect()->route('login');
        }

        $tags = Tag::all();
        $tag = $request->tag;
        $filterBy = $request->filterBy;
        $type = $request->type;
        $sort = $request->sort;
        $char = $request->char;

        $requested = new stdClass;
        $requested->filterBy = $filterBy;
        $requested->type = $type;
        $requested->tag = $tag;
        $requested->sort = $sort;
        $requested->char = $char;

        $filters = [
            ['name' => 'All', 'value' => 'all'],
            ['name' => 'Only Rated', 'value' => 'rated']
        ];

        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];

        $sortMethods = [
            ['name' => 'Recent', 'value' => 'recent'],
            ['name' => 'Title', 'value' => 'title'],
            ['name' => 'Score', 'value' => 'averageRating'],
            ['name' => 'Views', 'value' => 'viewCount'],
            ['name' => 'Popular', 'value' => 'likeCount']
        ];

        $characters = range('A', 'Z');

        switch ($filterBy) {
            case 'all':
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::where('title', 'LIKE', "{$char}%")
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            $posts = Post::whereLikedBy($user->id)
                                ->where('status', '=', 'published')
                                ->with('likeCounter')->get();
                        }
                    }
                }
                break;
            case 'rated':
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::where('title', 'LIKE', "{$char}%")
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            $posts = Post::whereLikedBy($user->id)
                                ->where('status', '=', 'published')
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')->get();
                        }
                    }
                }
                break;
            default:
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::where('title', 'LIKE', "{$char}%")
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            $posts = Post::whereLikedBy($user->id)
                                ->where('status', '=', 'published')
                                ->with('likeCounter')->get();
                        }
                    }
                }
                break;
        }

        switch ($sort) {
            case 'title':
                $posts = $posts->sortBy('title');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
            case 'viewCount':
                $posts = $posts->sortByDesc('viewCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));

            case 'likeCount':
                $posts = $posts->sortByDesc('likeCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
            case 'recent':
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;

            default:
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
        }
    }

    public function likePost($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Post::find($id)->like($userId);

            return Redirect::back()->with('success', 'Post Like successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function unlikePost($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Post::find($id)->unlike($userId);

            return Redirect::back()->with('success', 'Post Like undo successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    //public seasrch posts
    public function filter(Request $request)
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $tags = Tag::all();
        $tag = $request->tag;
        $type = $request->type;
        $sort = $request->sort;
        $char = $request->char;

        $requested = new stdClass;
        $requested->type = $type;
        $requested->tag = $tag;
        $requested->sort = $sort;
        $requested->char = $char;

        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];

        $sortMethods = [
            ['name' => 'Recent', 'value' => 'recent'],
            ['name' => 'Title', 'value' => 'title'],
            ['name' => 'Score', 'value' => 'averageRating'],
            ['name' => 'Views', 'value' => 'viewCount'],
            ['name' => 'Popular', 'value' => 'likeCount']
        ];

        $characters = range('A', 'Z');

        if ($tag != null) {
            if ($type != null) {
                if ($char != null) {
                    $posts = Post::withAnyTag($tag)
                        ->where('status', '=', 'published')
                        ->where('type', $type)
                        ->where('title', 'LIKE', "{$char}%")
                        ->get();
                } else {
                    $posts = Post::withAnyTag($tag)
                        ->where('status', '=', 'published')
                        ->where('type', $type)
                        ->get();
                }
            } else {
                if ($char != null) {
                    $posts = Post::withAnyTag($tag)
                        ->where('status', '=', 'published')
                        ->where('title', 'LIKE', "{$char}%")
                        ->get();
                } else {
                    $posts = Post::withAnyTag($tag)
                        ->where('status', '=', 'published')
                        ->get();
                }
            }
        } else {
            if ($type != null) {
                if ($char != null) {
                    $posts = Post::where('type', $type)
                        ->where('status', '=', 'published')
                        ->where('title', 'LIKE', "{$char}%")
                        ->get();
                } else {
                    $posts = Post::where('type', $type)
                        ->where('status', '=', 'published')
                        ->get();
                }
            } else {
                if ($char != null) {
                    $posts = Post::where('title', 'LIKE', "{$char}%")
                        ->where('status', '=', 'published')
                        ->get();
                } else {
                    $posts = Post::where('status', '=', 'published')->get();
                }
            }
        }

        //SWITCH ORDER THE POSTS
        switch ($sort) {
            case 'title':
                $posts = $posts->sortBy('title');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
            case 'viewCount':
                $posts = $posts->sortByDesc('viewCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));

            case 'likeCount':
                $posts = $posts->sortByDesc('likeCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
                break;
            case 'recent':
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
                break;

            default:
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
                break;
        }
    }

    public function paginate($posts, $perPage = 18, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $posts instanceof Collection ? $posts : Collection::make($posts);
        $posts = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $posts;
    }

    public function seasonalRanking()
    {
        $currentSeason = DB::table('tagging_tags')->where('flag', '1')->first();
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        if ($currentSeason == null) {
            $op_count = Post::where('status', '=', 'published')->where('type', 'op')->count();
            $ed_count = Post::where('status', '=', 'published')->where('type', 'ed')->count();

            $openings = Post::where('status', '=', 'published')->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $endings = Post::where('status', '=', 'published')->where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            return view('public.posts.ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'score_format'));
        } else {
            //search the current season and the posts
            $openings = Post::withAnyTag($currentSeason->name)
                ->where('status', '=', 'published')
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();
            $op_count = $openings->count();

            $endings = Post::withAnyTag($currentSeason->name)
                ->where('status', '=', 'published')
                ->where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();
            $ed_count = $endings->count();


            //dd($currentSeason, $op_count, $ed_count, $openings, $endings);

            return view('public.posts.ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'currentSeason', 'score_format'));
        }
    }
    public function globalRanking()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $getOpenings = Post::where('status', '=', 'published')->where('type', 'op')
            ->orderBy('title', 'asc')
            ->get();
        $op_count = $getOpenings->count();

        $openings = $getOpenings->sortByDesc('averageRating')->take(100);

        $getEndings = Post::where('status', '=', 'published')->where('type', 'ed')
            ->orderBy('title', 'asc')
            ->get();
        $ed_count = $getEndings->count();

        $endings = $getEndings->sortByDesc('averageRating')->take(100);

        return view('public.posts.ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'score_format'));
    }

    public function count_views($post)
    {
        if (!Session::has('page_visited_' . $post->id)) {
            DB::table('posts')
                ->where('id', $post->id)
                ->increment('viewCount');
            Session::put('page_visited_' . $post->id, true);
        }
    }
    
}
