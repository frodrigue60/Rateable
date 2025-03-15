<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Song;
use Conner\Tagging\Model\Tag;
use stdClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SongVariant;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $score_formats = [
            ['name' => ' 100 Point (55/100)', 'value' => 'POINT_100'],
            ['name' => '10 Point Decimal (5.5/10)', 'value' => 'POINT_10_DECIMAL'],
            ['name' => '10 Point (5/10)', 'value' => 'POINT_10'],
            ['name' => '5 Star (3/5)', 'value' => 'POINT_5'],
        ];

        if (Auth::check()) {
            $user = Auth::user();
            return view('public.users.profile', compact('score_formats', 'user'));
        } else {
            return redirect()->route('/')->with('warning', 'Please login');
        }
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

    public function userList(Request $request, $userId)
    {
        //dd($userId);
        $user = User::find($userId)->select('id', 'score_format', 'image', 'banner','name')->first();

        $score_format = $user->score_format;

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

        $requested->year = $request->year;
        $requested->season = $request->season;

        $years = $this->SeasonsYears($tags)['years'];
        $seasons = $this->SeasonsYears($tags)['seasons'];

        $filters = $this->filterTypesSortChar()['filters'];
        $types = $this->filterTypesSortChar()['types'];
        $sortMethods = $this->filterTypesSortChar()['sortMethods'];
        $characters = $this->filterTypesSortChar()['characters'];

        $song_variants = null;
        //dd($char);

        switch ($filterBy) {
            case 'all':
                if ($request->year != null || $request->season != null) {
                    if ($request->year != null && $request->season != null) {
                        $tag = $request->season . ' ' . $request->year;
                    } else {
                        $tag = DB::table('tagging_tags')
                            ->where(function ($query) use ($request) {
                                if ($request->year != null) {
                                    $query->where('name', 'LIKE', '%' . $request->year . '%');
                                } else {
                                    $query->where('name', 'LIKE', '%' . $request->season . '%');
                                }
                            })
                            ->limit(4)
                            ->get()
                            ->pluck('name')
                            ->toArray();
                    }
                    if ($type != null) {
                        if ($char != null) {
                            /* $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char, $tag) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%")
                                        ->withAnyTag($tag);
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */
                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($tag) {
                                    $query->where('status', 'published')
                                        ->withAnyTag($tag);
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();
                            //dd($song_variants);
                        }
                    } else {
                        if ($char != null) {
                            /* $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char, $tag) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%")
                                        ->withAnyTag($tag);
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($tag) {
                                    $query->where('status', 'published')
                                        ->withAnyTag($tag);
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();
                            //dd($song_variants);
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published');
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        }
                        //dd($song_variants);
                    } else {
                        if ($char != null) {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        }
                    }
                }
                break;
            case 'rated':
                if ($request->year != null || $request->season != null) {
                    if ($request->year != null && $request->season != null) {
                        $tag = $request->season . ' ' . $request->year;
                    } else {
                        $tag = DB::table('tagging_tags')
                            ->where(function ($query) use ($request) {
                                if ($request->year != null) {
                                    $query->where('name', 'LIKE', '%' . $request->year . '%');
                                } else {
                                    $query->where('name', 'LIKE', '%' . $request->season . '%');
                                }
                            })
                            ->limit(4)
                            ->get()
                            ->pluck('name')
                            ->toArray();
                    }
                    if ($type != null) {
                        if ($char != null) {
                            /* ONLY RATED, HAS TYPE, HAS SEASON */
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->withAnyTag($tag)
                                ->with(['post' => function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                }])
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('reactionsCounter')
                                //->with('post')
                                ->get();
                            //dd($songs);
                        } else {
                            /* ONLY RATED, TYPE, SEASON */
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->withAnyTag($tag)
                                ->with(['post' => function ($query) {
                                    $query->where('status', 'published');
                                }])
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('reactionsCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('reactionsCounter')
                                ->get();
                        } else {
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('reactionsCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            /* ONLY RATED, TYPE, CHAR */

                            /* $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->join('ratings', 'song_variants.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* ONLY RATED, TYPE */

                            /* $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->with(['post' => function ($query) {
                                    $query->where('status', 'published');
                                }])
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->join('ratings', 'song_variants.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        }
                    } else {
                        if ($char != null) {
                            /* ONLY RATED, CHAR SELECT */

                            /* $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'song_variants.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* ONLY RATED */

                            /* $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->with(['post' => function ($query) {
                                    $query->where('status', 'published');
                                }])
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'song_variants.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        }
                    }
                }
                break;
            default:
                if ($request->year != null || $request->season != null) {
                    if ($request->year != null && $request->season != null) {
                        $tag = $request->season . ' ' . $request->year;
                    } else {
                        $tag = DB::table('tagging_tags')
                            ->where(function ($query) use ($request) {
                                if ($request->year != null) {
                                    $query->where('name', 'LIKE', '%' . $request->year . '%');
                                } else {
                                    $query->where('name', 'LIKE', '%' . $request->season . '%');
                                }
                            })
                            ->limit(4)
                            ->get()
                            ->pluck('name')
                            ->toArray();
                    }
                    if ($type != null) {
                        if ($char != null) {
                            /* $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char, $tag) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%")
                                        ->withAnyTag($tag);
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */
                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($tag) {
                                    $query->where('status', 'published')
                                        ->withAnyTag($tag);
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();
                            //dd($song_variants);
                        }
                    } else {
                        if ($char != null) {
                            /* $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char, $tag) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%")
                                        ->withAnyTag($tag);
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($tag) {
                                    $query->where('status', 'published')
                                        ->withAnyTag($tag);
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();
                            //dd($song_variants);
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published');
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        }
                        //dd($song_variants);
                    } else {
                        if ($char != null) {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('reactionsCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->with('reactionsCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        }
                    }
                }
                break;
        }

        //dd($song_variants);

        //$songs = $this->setScore($songs, $score_format);
        //$songs = $this->sort($sort, $songs);
        //$songs = $this->paginate($songs, 24)->withQueryString();
        $song_variants = $this->setScoreOnlyVariants($song_variants, $score_format);
        $song_variants = $this->sort_variants($sort, $song_variants);
        $song_variants = $this->paginate($song_variants);

        //dd($song_variants);
        //dd($songs);
        if ($request->ajax()) {
            $view = view('layouts.variant.cards', compact('song_variants'))->render();
            return response()->json(['html' => $view, "lastPage" => $song_variants->lastPage()]);
        }
        //dd($songs);
        return view('public.songs.filter', compact('seasons', 'years', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
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
                $songs = $songs->sortBy(function ($song) {
                    return $song->post->title;
                });
                return $songs;
                break;
            case 'averageRating':
                $songs = $songs->sortByDesc('averageRating');
                return $songs;
            case 'view_count':
                $songs = $songs->sortByDesc('view_count');
                return $songs;

            case 'likeCount':
                $songs = $songs->sortByDesc('likeCount');
                return $songs;
                break;
            case 'recent':
                $songs = $songs->sortByDesc('created_at');
                return $songs;
                break;

            default:
                $songs = $songs->sortBy(function ($song) {
                    return $song->post->title;
                });
                return $songs;
                return $songs;
                break;
        }
    }

    public function setScore($songs, $score_format)
    {
        $songs->each(function ($song) use ($score_format) {
            $song->score = null;
            $song->user_score = null;
            switch ($score_format) {
                case 'POINT_100':
                    $song->score = round($song->averageRating);
                    if ($song->rating != null) {
                        $song->user_score = round($song->rating);
                    }

                    break;
                case 'POINT_10_DECIMAL':
                    $song->score = round($song->averageRating / 10, 1);
                    if ($song->rating != null) {
                        $song->user_score = round($song->rating / 10, 1);
                    }

                    break;
                case 'POINT_10':
                    $song->score = round($song->averageRating / 10);
                    if ($song->rating != null) {
                        $song->user_score = round($song->rating / 10);
                    }

                    break;
                case 'POINT_5':
                    $song->score = round($song->averageRating / 20);
                    if ($song->rating != null) {
                        $song->user_score = round($song->rating / 20);
                    }

                    break;
                default:
                    $song->score = round($song->averageRating / 10);
                    if ($song->rating != null) {
                        $song->user_score = round($song->rating / 10);
                    }

                    break;
            }
        });
        return $songs;
    }

    public function uploadProfilePic(Request $request)
    {
        if ($request->hasFile('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return redirect(route('profile'))->with('status', $errors);
            }

            //$user_email = Auth::user()->email;
            $user_id = Auth::user()->id;
            $old_user_image = Auth::user()->image;

            $file_type = $request->image->extension();
            $file_name = 'profile_' . time() . '.' . $file_type;

            Storage::disk('public')->delete('/profile/' . $old_user_image);
            $request->image->storeAs('profile', $file_name, 'public');

            DB::table('users')
                ->where('id', $user_id)
                ->update(['image' => $file_name]);

            return redirect(route('profile'))->with('success', 'Image uploaded successfully!');
        } else {
            //dd($request->all());

            $user = Auth::user();

            DB::table('users')
                ->where('id', $user->id)
                ->update(['image' => $request->profile_pic_url]);

            return redirect(route('profile'))->with('success', 'Image uploaded successfully!');
        }
        return redirect(route('profile'))->with('warning', 'File not found');
    }
    public function uploadBannerPic(Request $request)
    {
        if ($request->hasFile('banner')) {

            $validator = Validator::make($request->all(), [
                'banner' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return redirect(route('profile'))->with('error', $errors);
            }

            $user_id = Auth::user()->id;

            $file_type = $request->banner->extension();
            $file_name = 'banner_' . time() . '.' . $file_type;

            if (Auth::user()->banner != null) {
                Storage::disk('public')->delete('/banner/' . Auth::user()->banner);
            }

            $request->banner->storeAs('banner', $file_name, 'public');

            DB::table('users')
                ->where('id', $user_id)
                ->update(['banner' => $file_name]);

            return redirect(route('profile'))->with('success', 'Image uploaded successfully!');
        } else {
            //dd($request->all());

            $user = Auth::user();

            DB::table('users')
                ->where('id', $user->id)
                ->update(['banner' => $request->banner_pic_url]);

            return redirect(route('profile'))->with('success', 'Image uploaded successfully!');
        }
        return redirect(route('profile'))->with('warning', 'File not found');
    }
    public function changeScoreFormat(Request $request)
    {
        if ($request->score_format == 'null') {
            return redirect()->back()->with('warning', 'score method not changed');
        }

        $validator = Validator::make($request->all(), [
            'score_format' => 'required|in:POINT_100,POINT_10_DECIMAL,POINT_10,POINT_5'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with('error', '¡Ooops!');
        }


        if (Auth::check()) {
            $user = Auth::user();
            $user = User::find($user->id);
            $user->score_format = $request->score_format;
            $user->update();

            return redirect()->back()->with('success', 'score method changed successfully');
        } else {
            return redirect(route('login'));
        }
    }

    public function welcome()
    {
        return view('welcome');
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
    public function filterTypesSortChar()
    {
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

        $data = [
            'filters' => $filters,
            'types' => $types,
            'sortMethods' => $sortMethods,
            'characters' => $characters
        ];
        return $data;
    }

    public function setScoreOnlyVariants($variantsArray, $score_format)
    {
        /* $variantsArray->each(function ($variant) use ($score_format) {
            $variant->score = null;
            $variant->user_score = null;
            switch ($score_format) {
                case 'POINT_100':
                    $variant->score = round($variant->averageRating);
                    if ($variant->rating != null) {
                        $variant->user_score = round($variant->rating);
                    }

                    break;
                case 'POINT_10_DECIMAL':
                    $variant->score = round($variant->averageRating / 10, 1);
                    if ($variant->rating != null) {
                        $variant->user_score = round($variant->rating / 10, 1);
                    }

                    break;
                case 'POINT_10':
                    $variant->score = round($variant->averageRating / 10);
                    if ($variant->rating != null) {
                        $variant->user_score = round($variant->rating / 10);
                    }

                    break;
                case 'POINT_5':
                    $variant->score = round($variant->averageRating / 20);
                    if ($variant->rating != null) {
                        $variant->user_score = round($variant->rating / 20);
                    }

                    break;
                default:
                    $variant->score = round($variant->averageRating / 10);
                    if ($variant->rating != null) {
                        $variant->user_score = round($variant->rating / 10);
                    }
                    break;
            }
        });
        return $variantsArray; */

        $variantsArray->each(function ($variant) use ($score_format) {
            $factor = 1;

            switch ($score_format) {
                case 'POINT_100':
                    $factor = 1;
                    break;
                case 'POINT_10_DECIMAL':
                    $factor = 0.1;
                    break;
                case 'POINT_10':
                    $factor = 1 / 10;
                    break;
                case 'POINT_5':
                    $factor = 1 / 20;
                    break;
                default:
                    $factor = 1 / 10;
                    break;
            }

            $variant->score = round($variant->averageRating * $factor);
            $variant->user_score = $variant->rating ? round($variant->rating * $factor) : null;
        });

        return $variantsArray;
    }

    public function sort_variants($sort, $song_variants)
    {
        //dd($song_variants);
        switch ($sort) {
            case 'title':
                $song_variants = $song_variants->sortBy(function ($song_variant) {
                    return $song_variant->song->post->title;
                });
                return $song_variants;
                break;

            case 'averageRating':
                $song_variants = $song_variants->sortByDesc('averageRating');
                return $song_variants;
                break;

            case 'view_count':
                $song_variants = $song_variants->sortByDesc('views');
                return $song_variants;
                break;

            case 'likeCount':
                $song_variants = $song_variants->sortByDesc('likeCount');
                return $song_variants;
                break;

            case 'recent':
                $song_variants = $song_variants->sortByDesc('created_at');
                return $song_variants;
                break;

            default:
                $song_variants = $song_variants->sortByDesc('created_at');
                return $song_variants;
                break;
        }
    }
}
