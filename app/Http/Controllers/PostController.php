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
use Symfony\Component\Console\Output\ConsoleOutput;


use stdClass;

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
        $popular = $this->setScore($popular, $score_format);
        //dd($popular);

        $viewed = Song::with(['post'])
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('view_count')
            ->take(15);
        $viewed = $this->setScore($viewed, $score_format);
        //dd($viewed);

        $openings = Song::with(['post'])
            ->where('type', 'OP')
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5);
        $openings = $this->setScore($openings, $score_format);

        $endings = Song::with(['post'])
            ->where('type', 'ED')
            ->whereHas('post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5);
        $endings = $this->setScore($endings, $score_format);
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

        $posts = $this->paginate($posts)->withQueryString();

        return view('public.posts.filter', compact('posts', 'tags', 'characters', 'requested'));
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
            $songs = $this->setScore($songs, $score_format);
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
            $songs = $this->setScore($songs, $score_format);

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
            $songs = $this->setScore($songs, $score_format);

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
            $songs = $this->setScore($songs, $score_format);

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

            if (blank($score)) {
                return redirect()->back()->with('warning', 'Score can not be null');
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
                        $post->rateOnce(intval($score * 10));
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (can use decimals)');
                    }
                    break;

                case 'POINT_10':
                    if (($score >= 1) && ($score <= 10)) {
                        $post->rateOnce(intval($score * 10));
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
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                            //dd($songs);
                        }
                    }
                }
                break;
            default:
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
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

        $songs = $this->setScore($songs, $score_format);
        $songs = $this->sort($sort, $songs);
        $songs = $this->paginate($songs)->withQueryString();    
        //dd($songs);
        return view('public.songs.filter', compact('songs', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
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

                    $songs = Song::with(['post'])
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })
                        ->where('type', $type)
                        ->get();
                } else {
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
                    $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })
                        ->where('type', $type)
                        ->get();
                } else {
                    $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })->where('type', $type)->get();
                }
            } else {
                if ($char != null) {
                    $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) use ($char) {
                            $query
                                ->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })->get();
                } else {

                    $songs = Song::with(['post'])
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })->get();
                }
            }
        }


        $songs = $this->setScore($songs, $score_format);
        //dd($songs);
        $songs = $this->sort($sort, $songs);
        $songs = $this->paginate($songs)->withQueryString();
        //dd($songs);
        return view('public.songs.filter', compact('songs', 'tags', 'requested', 'sortMethods', 'types', 'characters'));
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
            $this->setScore($openings, $score_format);

            $endings = Song::with(['post'])
                ->where('type', 'ED')
                ->get()->sortByDesc('averageRating')->take(100);
            $this->setScore($endings, $score_format);

            return view('public.posts.ranking', compact('openings', 'endings', 'score_format'));
        } else {
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
            $this->setScore($openings, $score_format);

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
            $this->setScore($endings, $score_format);



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
        $openings = $this->setScore($openings, $score_format);
        //$openings = $this->paginate($openings, 10)->withQueryString();

        $getEndings = Song::with(['post'])
            ->where('type', 'ED')
            ->get();

        $endings = $getEndings->sortByDesc('averageRating')->take(100);
        $endings = $this->setScore($endings, $score_format);
        //$endings = $this->paginate($endings, 10)->withQueryString();
        //dd($openings,$endings);

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
}
