<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Song;
use Conner\Tagging\Model\Tag;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use stdClass;
use App\Models\SongVariant;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        /*  $recently = Song::with(['post'])
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('created_at')
            ->take(25); */

        $recently = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('created_at')
            ->take(25);

        //dd($recently);

        /* $popular = Song::with(['post'])
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('likeCount')
            ->take(15); */

        $popular = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('likeCount')
            ->take(15);

        $popular = $this->setScoreOnlyVariants($popular, $score_format);

        //dd($popular);

        /* $viewed = Song::with(['post'])
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('view_count')
            ->take(15); */

        $viewed = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('views')
            ->take(15);

        $viewed = $this->setScoreOnlyVariants($viewed, $score_format);

        //dd($viewed);

        /* $openings = Song::with(['post'])
            ->where('type', 'OP')
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5); */

        $openings = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->whereHas('song', function ($query) {
                $query->where('type', 'OP');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5);

        $openings = $this->setScoreOnlyVariants($openings, $score_format);

        //dd($openings);

        /* $endings = Song::with(['post'])
            ->where('type', 'ED')
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5); */
        $endings = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->whereHas('song', function ($query) {
                $query->where('type', 'ED');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5);

        $endings = $this->setScore($endings, $score_format);

        //dd($endings);

        return view('index', compact('openings', 'endings', 'recently', 'popular', 'viewed', 'score_format'));
    }

    public function animes(Request $request)
    {
        $tag = $request->tag;
        $char = $request->char;

        $requested = new stdClass;
        $requested->tag = $tag;
        $requested->char = $char;
        $requested->year = $request->year;
        $requested->season = $request->season;

        $posts = Post::all();
        $tags = Tag::all();
        $characters = range('A', 'Z');

        $years = $this->SeasonsYears($tags)['years'];
        $seasons = $this->SeasonsYears($tags)['seasons'];

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
            if ($char != null) {
                $posts = Post::withAnyTag($tag)
                    ->where('status', 'published')
                    ->where('title', 'LIKE', "{$char}%")->get();
            } else {
                $posts = Post::withAnyTag($tag)
                    ->where('status', 'published')->get();
            }
        } else {
            if ($char != null) {
                $posts = Post::where('status', 'published')
                    ->where('title', 'LIKE', "{$char}%")->get();
            } else {
                $posts = Post::where('status', 'published')->get();
            }
        }
        $posts = $posts->sortBy(function ($post) {
            return $post->title;
        });

        $posts = $this->paginate($posts, 24)->withQueryString();

        if ($request->ajax()) {
            //error_log('new ajax request');
            $view = view('layouts.posts-cards', compact('posts'))->render();
            return response()->json(['html' => $view, "lastPage" => $posts->lastPage()]);
        }

        return view('public.posts.filter', compact('characters', 'requested', 'seasons', 'years'));
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
        } else {
            $score_format = null;
        }

        $post = Post::with('songs')->find($id);

        $openings = $post->songs->filter(function ($song) {
            return $song->type === 'OP';
        });

        $endings = $post->songs->filter(function ($song) {
            return $song->type === 'ED';
        });

        $tags = $post->tagged;

        //dd($openings, $endings, $tags);

        $endings = $this->setScoreToSongVariants($endings, $score_format);

        $openings = $this->setScoreToSongVariants($openings, $score_format);

        //dd($openings,$endings);

        return view('public.posts.show', compact('post', 'tags', 'openings', 'endings', 'score_format'));
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

        $tags = DB::table('tagging_tags')
            ->orderBy('name', 'desc')
            ->take(5)
            ->get();

        $song_variants = Song::with('songVariants')
            ->where('type', 'OP')
            ->when($currentSeason, function ($query) use ($currentSeason) {
                return $query->withAnyTag($currentSeason->name);
            })
            ->get()
            ->flatMap(function ($song) {
                return $song->songVariants;
            });

        $song_variants = $this->setScoreOnlyVariants($song_variants, $score_format);

        return view('public.posts.seasonal', compact('song_variants', 'tags', 'score_format', 'currentSeason'));
    }
    public function endings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $currentSeason = DB::table('tagging_tags')->where('flag', '1')->first();

        $tags = DB::table('tagging_tags')
            ->orderBy('name', 'desc')
            ->take(5)
            ->get();

        $song_variants = Song::with('songVariants')
            ->where('type', 'ED')
            ->when($currentSeason, function ($query) use ($currentSeason) {
                return $query->withAnyTag($currentSeason->name);
            })
            ->get()
            ->flatMap(function ($song) {
                return $song->songVariants;
            });

        $song_variants = $this->setScoreOnlyVariants($song_variants, $score_format);

        return view('public.posts.seasonal', compact('song_variants', 'tags', 'score_format', 'currentSeason'));
    }

    public function ratePost(Request $request, $id)
    {
        if (Auth::check()) {
            $post = Post::find($id);

            //dd($request->all());
            $validator = Validator::make($request->all(), [
                'comment' => 'nullable|string|max:255',
                'score' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                $messageBag = $validator->getMessageBag();
                return redirect()
                    ->back()
                    ->with('error', $messageBag);
            } else {
                $score = $request->score;
            }

            switch (Auth::user()->score_format) {
                case 'POINT_100':
                    if (($score >= 1) && ($score <= 100)) {
                        $post->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;

                case 'POINT_10_DECIMAL':
                    if (($score >= 1) && ($score <= 10)) {
                        $post->rateOnce($score * 10);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (can use decimals)');
                    }
                    break;

                case 'POINT_10':
                    if (($score >= 1) && ($score <= 10)) {
                        $post->rateOnce($score * 10);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (only integer numbers)');
                    }
                    break;

                case 'POINT_5':
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
                    if (($score >= 1) && ($score <= 10)) {
                        $post->rateOnce($score * 10);
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
                                ->with('likeCounter')
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
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */
                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($tag) {
                                    $query->where('status', 'published')
                                        ->withAnyTag($tag);
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char, $tag) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%")
                                        ->withAnyTag($tag);
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($tag) {
                                    $query->where('status', 'published')
                                        ->withAnyTag($tag);
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published');
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->with('likeCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->with('likeCounter')
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
                                //->with('likeCounter')
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
                                //->with('likeCounter')
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
                                //->with('likeCounter')
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
                                //->with('likeCounter')
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
                                //->with('likeCounter')
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
                                ->with('likeCounter')
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
                                //->with('likeCounter')
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
                                ->with('likeCounter')
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
                                //->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'song_variants.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
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
                                //->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'song_variants.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
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
                                ->with('likeCounter')
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
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */
                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($tag) {
                                    $query->where('status', 'published')
                                        ->withAnyTag($tag);
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char, $tag) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%")
                                        ->withAnyTag($tag);
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($tag) {
                                    $query->where('status', 'published')
                                        ->withAnyTag($tag);
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song', function ($query) use ($type) {
                                    $query->where('type', $type);
                                })
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published');
                                })
                                ->with('likeCounter')
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
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->with('likeCounter')
                                ->whereLikedBy($user->id)
                                ->get();

                            //dd($song_variants);
                        } else {
                            /* $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get(); */

                            $song_variants = SongVariant::with(['song'])
                                ->whereHas('song.post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->with('likeCounter')
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

        //dd($songs);
        if ($request->ajax()) {
            $view = view('layouts.song-variant-cards', compact('song_variants'))->render();
            return response()->json(['html' => $view, "lastPage" => $song_variants->lastPage()]);
        }
        //dd($songs);
        return view('public.songs.filter', compact('seasons', 'years', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
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
    public function themes(Request $request)
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
        $char = $request->character;

        $requested = new stdClass;
        $requested->type = $type;
        $requested->tag = $tag;
        $requested->sort = $sort;
        $requested->char = $char;

        $requested->year = $request->year;
        $requested->season = $request->season;

        $years = $this->SeasonsYears($tags)['years'];
        $seasons = $this->SeasonsYears($tags)['seasons'];

        $types = $this->filterTypesSortChar()['types'];
        $sortMethods = $this->filterTypesSortChar()['sortMethods'];
        $characters = $this->filterTypesSortChar()['characters'];

        $song_variants = null;

        //dd($char);

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
            //dd($tag);
            if ($type != null) {
                if ($char != null) {
                    /* $songs = Song::with(['post'])
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })
                        ->where('type', $type)
                        ->get(); */

                    $song_variants = SongVariant::with(['song'])
                        ->whereHas('song.post', function ($query) use ($char, $tag) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->withAnyTag($tag);
                        })
                        ->whereHas('song', function ($query) use ($type) {
                            $query->where('type', $type);
                        })
                        ->get();
                } else {
                    /*  $songs = Song::with(['post'])
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })
                        ->where('type', $type)
                        ->get(); */

                    $song_variants = SongVariant::with(['song'])
                        ->whereHas('song.post', function ($query) use ($char, $tag) {
                            $query->where('status', 'published')
                                ->withAnyTag($tag);
                        })
                        ->whereHas('song', function ($query) use ($type) {
                            $query->where('type', $type);
                        })
                        ->get();
                }
            } else {
                if ($char != null) {
                    /* $songs = Song::with(['post'])
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })->get(); */
                    $song_variants = SongVariant::with(['song'])
                        ->whereHas('song.post', function ($query) use ($tag, $char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%")
                                ->withAnyTag($tag);
                        })
                        ->get();
                } else {
                    /* $songs = Song::with(['post'])
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })
                        ->get(); */
                    $song_variants = SongVariant::with(['song'])
                        ->whereHas('song.post', function ($query) use ($tag) {
                            $query->where('status', 'published')
                                ->withAnyTag($tag);
                        })
                        ->get();
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
                        ->get(); */
                    $song_variants = SongVariant::with(['song'])
                        ->whereHas('song.post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })
                        ->whereHas('song', function ($query) use ($type) {
                            $query->where('type', $type);
                        })
                        ->get();
                } else {
                    /* $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })->where('type', $type)->get(); */

                    $song_variants = SongVariant::with(['song'])
                        ->whereHas('song.post', function ($query) {
                            $query->where('status', 'published');
                        })
                        ->whereHas('song', function ($query) use ($type) {
                            $query->where('type', $type);
                        })
                        ->get();
                }
                //dd($song_variants);
            } else {
                if ($char != null) {
                    /* $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) use ($char) {
                            $query
                                ->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })->get(); */

                    $song_variants = SongVariant::with(['song'])
                        ->whereHas('song.post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })->get();
                } else {
                    /* $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })->get(); */
                    $song_variants = SongVariant::with(['song'])
                        ->get();
                }
            }
        }

        //dd($song_variants);

        //$songs = $this->setScore($songs, $score_format);
        //$songs = $this->paginate($songs, 24)->withQueryString();
        //$songs = $this->sort($sort, $songs);

        $song_variants = $this->setScoreOnlyVariants($song_variants, $score_format);
        $song_variants = $this->sort_variants($sort, $song_variants);
        $song_variants = $this->paginate($song_variants);


        //dd($song_variants);

        if ($request->ajax()) {
            $view = view('layouts.song-variant-cards', compact('song_variants'))->render();
            return response()->json(['html' => $view, "lastPage" => $song_variants->lastPage()]);
        }

        return view('public.songs.filter', compact(/* 'song_variants', */'seasons', 'years', 'requested', 'sortMethods', 'types', 'characters'));
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

    public function setScoreToSongVariants($songsArray, $score_format)
    {
        $songsArray->each(function ($song) use ($score_format) {
            $song->songVariants->each(function ($variant) use ($score_format) {
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
        });

        return $songsArray;
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
                break;
        }
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

    public function seasonalRanking()
    {
        $currentSeason = DB::table('tagging_tags')->where('flag', '1')->first();

        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        if ($currentSeason == null) {

            $openings = SongVariant::with(['song'])
                ->whereHas('song.post', function ($query) {
                    $query->where('status', 'published');
                })
                ->whereHas('song', function ($query) {
                    $query->where('type', 'OP');
                })
                ->get()
                ->sortByDesc('averageRating')
                ->take(100);

            $endings = SongVariant::with(['song'])
                ->whereHas('song.post', function ($query) {
                    $query->where('status', 'published');
                })
                ->whereHas('song', function ($query) {
                    $query->where('type', 'ED');
                })
                ->get()
                ->sortByDesc('averageRating')
                ->take(100);

            $this->setScoreOnlyVariants($openings, $score_format);
            $this->setScoreOnlyVariants($endings, $score_format);

            return view('public.posts.ranking', compact('openings', 'endings', 'score_format'));
        } else {
            $openings = SongVariant::with(['song'])
                ->whereHas('song.post', function ($query) use ($currentSeason) {
                    $query->where('status', 'published')
                        ->withAnyTag($currentSeason->name);
                })
                ->whereHas('song', function ($query) {
                    $query->where('type', 'OP');
                })
                ->get()
                ->sortByDesc('averageRating')
                ->take(100);

            $endings = SongVariant::with(['song'])
                ->whereHas('song.post', function ($query) use ($currentSeason) {
                    $query->where('status', 'published')
                        ->withAnyTag($currentSeason->name);
                })
                ->whereHas('song', function ($query) {
                    $query->where('type', 'ED');
                })
                ->get()
                ->sortByDesc('averageRating')
                ->take(100);

            $this->setScoreOnlyVariants($openings, $score_format);
            $this->setScoreOnlyVariants($endings, $score_format);

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

        $getOpenings = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->whereHas('song', function ($query) {
                $query->where('type', 'OP');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(100);

        $openings = $getOpenings->sortByDesc('averageRating')->take(100);

        $openings = $this->setScoreOnlyVariants($openings, $score_format);

        $getEndings = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->whereHas('song', function ($query) {
                $query->where('type', 'ED');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(100);

        $endings = $getEndings->sortByDesc('averageRating')->take(100);

        $endings = $this->setScoreOnlyVariants($endings, $score_format);

        return view('public.posts.ranking', compact('openings', 'endings',  'score_format'));
    }

    /* public function count_views($post)
    {
        if (!Session::has('page_visited_' . $post->id)) {
            DB::table('posts')
                ->where('id', $post->id)
                ->increment('view_count');
            Session::put('page_visited_' . $post->id, true);
        }
    } */

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
}
