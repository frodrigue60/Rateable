<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Comment;
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
        $recently = Song::with(['post'])
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('created_at')
            ->take(25);

        $popular = Song::with(['post'])
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('likeCount')
            ->take(15);

        $viewed = Song::with(['post'])
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('view_count')
            ->take(15);

        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $openings = Song::with(['post'])
            ->where('type', 'OP')
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5);

        $endings = Song::with(['post'])
            ->where('type', 'ED')
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5);
        //dd($openings,$endings);

        return view('index', compact('openings', 'endings', 'recently', 'popular', 'viewed', 'score_format'));
    }

    public function animes(Request $request)
    {
        $tag = $request->tag;
        $char = $request->char;

        $requested = new stdClass;
        $requested->tag = $tag;
        $requested->char = $char;

        $posts = Post::all();
        $tags = Tag::all();
        $characters = range('A', 'Z');

        if ($tag != null) {
            if ($char != null) {
                $posts = Post::withAnyTag($tag)
                    ->where('status', 'published')
                    ->where('title', 'LIKE', "{$char}%")
                    ->get();
            } else {
                $posts = Post::withAnyTag($tag)
                    ->where('status', 'published')
                    ->get();
            }
        } else {
            if ($char != null) {
                $posts = Post::where('status', 'published')
                    ->where('title', 'LIKE', "{$char}%")
                    ->get();
            } else {
                $posts = Post::where('status', 'published')
                    ->get();
            }
        }
        $posts = $posts->sortBy(function ($post) {
            return $post->title;
        });
        $songs = $posts;

        $posts = $this->paginate($songs)->withQueryString();
        //$posts = $songs;

        return view('public.posts.filter-animes', compact('posts', 'tags', 'characters', 'requested'));
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
        $post = Post::with('songs')->find($id);
        $openings = $post->songs->filter(function ($song) {
            return $song->type === 'OP';
        });
        $endings = $post->songs->filter(function ($song) {
            return $song->type === 'ED';
        });

        $tags = $post->tagged;
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            return view('public.posts.show', compact('post', 'tags', 'openings', 'endings', 'score_format'));
        } else {
            return view('public.posts.show', compact('post', 'tags', 'openings', 'endings'));
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

            $songs = Song::with(['post'])
                ->where('type', 'OP')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('public.posts.seasonal', compact('songs', 'tags', 'score_format'));
        } else {

            $songs = Song::with(['post'])
                ->withAnyTag($currentSeason->name)
                ->whereHas('post', function ($query) {
                    $query->where('status', 'published')
                        ->orderBy('title', 'asc');
                })
                ->where('type', 'OP')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('public.posts.seasonal', compact('songs', 'tags', 'score_format', 'currentSeason'));
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

            $songs = Song::with(['post'])
                ->where('type', 'OP')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('public.posts.seasonal', compact('songs', 'tags', 'score_format'));
        } else {

            $songs = Song::with(['post'])
                ->withAnyTag($currentSeason->name)
                ->whereHas('post', function ($query) {
                    $query->where('status', 'published')
                        ->orderBy('title', 'asc');
                })
                ->where('type', 'ED')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('public.posts.seasonal', compact('songs', 'tags', 'score_format', 'currentSeason'));
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
            ['name' => 'Views', 'value' => 'view_count'],
            ['name' => 'Popular', 'value' => 'likeCount']
        ];

        $characters = range('A', 'Z');

        switch ($filterBy) {
            case 'all':
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            /* $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            /* $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            /* $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            /* $posts = Post::where('title', 'LIKE', "{$char}%")
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            /* $posts = Post::whereLikedBy($user->id)
                                ->where('status', '=', 'published')
                                ->with('likeCounter')->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                }
                break;
            case 'rated':
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            /* $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            /* $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::withAnyTag($tag)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            /* $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::where('type', $type)
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            /* $posts = Post::where('title', 'LIKE', "{$char}%")
                                ->where('status', '=', 'published')
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            /* $posts = Post::whereLikedBy($user->id)
                                ->where('status', '=', 'published')
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                }
                break;
            default:
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            /* $posts = Post::withAnyTag($tag)
                            ->where('status', '=', 'published')
                            ->where('type', $type)
                            ->where('title', 'LIKE', "{$char}%")
                            ->whereLikedBy($user->id)
                            ->with('likeCounter')
                            ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::withAnyTag($tag)
                            ->where('status', '=', 'published')
                            ->where('type', $type)
                            ->whereLikedBy($user->id)
                            ->with('likeCounter')
                            ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            /* $posts = Post::withAnyTag($tag)
                            ->where('status', '=', 'published')
                            ->where('title', 'LIKE', "{$char}%")
                            ->whereLikedBy($user->id)
                            ->with('likeCounter')
                            ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::withAnyTag($tag)
                            ->where('status', '=', 'published')
                            ->whereLikedBy($user->id)
                            ->with('likeCounter')
                            ->get(); */
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            /* $posts = Post::where('type', $type)
                            ->where('status', '=', 'published')
                            ->where('title', 'LIKE', "{$char}%")
                            ->whereLikedBy($user->id)
                            ->with('likeCounter')
                            ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            /* $posts = Post::where('type', $type)
                            ->where('status', '=', 'published')
                            ->whereLikedBy($user->id)
                            ->with('likeCounter')
                            ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            /* $posts = Post::where('title', 'LIKE', "{$char}%")
                            ->where('status', '=', 'published')
                            ->whereLikedBy($user->id)
                            ->with('likeCounter')
                            ->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            /* $posts = Post::whereLikedBy($user->id)
                            ->where('status', '=', 'published')
                            ->with('likeCounter')->get(); */
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                }
                break;
        }
        //SWITCH ORDER THE POSTS
        /* switch ($sort) {
            case 'title':
                $posts = $posts->sortBy('title');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
            case 'view_count':
                $posts = $posts->sortByDesc('view_count');
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
        } */
        $songs = $this->sort($sort, $songs);
        $songs = $this->paginate($songs)->withQueryString();
        return view('public.posts.filter', compact('songs', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
    }

    public function likePost($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            Post::find($id)->like($user->id);

            return Redirect::back()->with('success', 'Post Like successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function unlikePost($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            Post::find($id)->unlike($user->id);

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
            ['name' => 'Views', 'value' => 'view_count'],
            ['name' => 'Popular', 'value' => 'likeCount']
        ];

        $characters = range('A', 'Z');

        if ($tag != null) {
            if ($type != null) {
                if ($char != null) {
                    /* $posts = Post::withAnyTag($tag)
                        ->where('status', '=', 'published')
                        ->where('type', $type)
                        ->where('title', 'LIKE', "{$char}%")
                        ->get(); */

                    $songs = Song::with(['post'])
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })
                        ->where('type', $type)
                        ->get();
                } else {
                    /* $posts = Post::withAnyTag($tag)
                        ->where('status', '=', 'published')
                        ->where('type', $type)
                        ->get(); */
                    $songs = Song::with(['post'])
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })
                        ->where('type', $type)
                        ->get();
                }
            } else {
                if ($char != null) {
                    $songs = Song::with(['post'])
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })->get();
                } else {
                    //dd($request->all());
                    $songs = Song::with(['post'])
                        ->withAnyTag([$tag])
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })
                        ->get();
                }
            }
        } else {
            if ($type != null) {
                if ($char != null) {
                    /* $posts = Post::where('type', $type)
                        ->where('status', '=', 'published')
                        ->where('title', 'LIKE', "{$char}%")
                        ->get(); */
                    $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })
                        ->where('type', $type)
                        ->get();
                } else {
                    /* $posts = Post::where('type', $type)
                        ->where('status', '=', 'published')
                        ->get(); */
                    $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })->where('type', $type)->get();
                }
            } else {
                if ($char != null) {
                    /* $posts = Post::where('title', 'LIKE', "{$char}%")
                        ->where('status', '=', 'published')
                        ->get(); */
                    $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) use ($char) {
                            $query
                                ->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })->get();
                } else {
                    /* $posts = Post::where('status', '=', 'published')->get(); */
                    $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })->get();
                }
            }
        }

        //SWITCH ORDER THE POSTS
        /* switch ($sort) {
            case 'title':
                $posts = $posts->sortBy('title');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
            case 'view_count':
                $posts = $posts->sortByDesc('view_count');
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
        } */
        $songs = $this->sort($sort, $songs);
        $songs = $this->paginate($songs)->withQueryString();
        return view('public.posts.filter', compact('songs', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
    }

    public function paginate($songs, $perPage = 18, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $songs instanceof Collection ? $songs : Collection::make($songs);
        $songs = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $songs;
    }

    public function sort($sort, $songs)
    {
        switch ($sort) {
            case 'title':
                //$songs = $songs->sortBy('title');
                //$posts = $this->paginate($posts)->withQueryString();
                $songs = $songs->sortBy(function ($song) {
                    return $song->post->title;
                });
                return $songs;
                break;
            case 'averageRating':
                $songs = $songs->sortByDesc('averageRating');
                //$posts = $this->paginate($posts)->withQueryString();
                return $songs;
            case 'view_count':
                $songs = $songs->sortByDesc('view_count');
                //$posts = $this->paginate($posts)->withQueryString();
                return $songs;

            case 'likeCount':
                $songs = $songs->sortByDesc('likeCount');
                //$posts = $this->paginate($posts)->withQueryString();
                return $songs;
                break;
            case 'recent':
                $songs = $songs->sortByDesc('created_at');
                //$posts = $this->paginate($posts)->withQueryString();
                return $songs;
                break;

            default:
                //$songs = $songs->sortByDesc('created_at');
                //$posts = $this->paginate($posts)->withQueryString();
                $songs = $songs->sortBy(function ($song) {
                    return $song->post->title;
                });
                return $songs;
                return $songs;
                break;
        }
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

            $openings = Song::with(['post'])
                ->where('type', 'OP')
                ->get()->sortByDesc('averageRating')->take(100);

            $endings = Song::with(['post'])
                ->where('type', 'ED')
                ->get()->sortByDesc('averageRating')->take(100);

            return view('public.posts.ranking', compact('openings', 'endings', 'score_format'));
        } else {
            //search the current season and the posts
            $endings = Song::with(['post'])
                ->withAnyTag($currentSeason->name)
                ->whereHas('post', function ($query) {
                    $query->where('status', 'published')
                        ->orderBy('title', 'asc');
                })
                ->where('type', 'ED')

                ->get()
                ->sortByDesc('averageRating')
                ->take(100);

            $openings = Song::with(['post'])
                ->withAnyTag($currentSeason->name)
                ->whereHas('post', function ($query) {
                    $query->where('status', 'published')
                        ->orderBy('title', 'asc');
                })
                ->where('type', 'OP')
                ->get()
                ->sortByDesc('averageRating')
                ->take(100);


            //dd($currentSeason, $op_count, $ed_count, $openings, $endings);

            return view('public.posts.ranking', compact('openings', 'endings', 'currentSeason', 'score_format'));
        }
    }
    public function globalRanking()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $getOpenings = Song::with(['post'])
            ->where('type', 'OP')
            ->get();

        $openings = $getOpenings->sortByDesc('averageRating')->take(100);

        $getEndings = Song::with(['post'])
            ->where('type', 'ED')
            ->get();

        $endings = $getEndings->sortByDesc('averageRating')->take(100);

        return view('public.posts.ranking', compact('openings', 'endings',  'score_format'));
    }

    public function count_views($post)
    {
        if (!Session::has('page_visited_' . $post->id)) {
            DB::table('posts')
                ->where('id', $post->id)
                ->increment('view_count');
            Session::put('page_visited_' . $post->id, true);
        }
    }

    public function likeComment($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            Comment::find($id)->like($user->id);
            return Redirect::back()->with('success', 'Comment Like successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function unlikeComment($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            Comment::find($id)->unlike($user->id);

            return Redirect::back()->with('success', 'Comment Like undo successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

}
