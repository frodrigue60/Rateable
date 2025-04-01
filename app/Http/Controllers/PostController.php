<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Season;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use stdClass;
use App\Models\SongVariant;
use App\Models\Year;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::check() ? Auth::User() : null;

        $recently = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('created_at')
            ->take(25);
        $popular = $this->setScoreOnlyVariants($recently, $user);


        $popular = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('likeCount')
            ->take(15);

        $popular = $this->setScoreOnlyVariants($popular, $user);

        $viewed = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) {
                $query->where('status', '=', 'published');
            })
            ->get()
            ->sortByDesc('views')
            ->take(15);

        $viewed = $this->setScoreOnlyVariants($viewed, $user);

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

        $openings = $this->setScoreOnlyVariants($openings, $user);

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

        $endings = $this->setScoreOnlyVariants($endings, $user);

        //dd($endings);

        return view('index', compact('openings', 'endings', 'recently', 'popular', 'viewed'));
    }

    public function animes(Request $request)
    {
        $name = $request->name;
        $season = Season::where('name', $request->season)->first();
        $year = Year::where('name', $request->year)->first();

        $requested = new stdClass;
        $requested->name = $name;
        $requested->year = $request->year;
        $requested->season = $request->season;

        $seasons = Season::all();
        $years = Year::all();

        $posts = Post::where('status', 'published')
            ->when($season, function ($query, $season) {
                $query->where('season_id', $season->id);
            })
            ->when($year, function ($query, $year) {
                $query->where('year_id', $year->id);
            })
            ->when($name, function ($query, $name) {
                $query->where('title', 'LIKE', "%$name%");
            })
            ->get();

        $posts = $posts->sortBy(function ($post) {
            return $post->title;
        });

        $posts = $this->paginate($posts, 24)->withQueryString();

        if ($request->ajax()) {
            //error_log('new ajax request');
            $view = view('layouts.post.cards', compact('posts'))->render();
            return response()->json(['html' => $view, "lastPage" => $posts->lastPage()]);
        }

        return view('public.posts.filter', compact('requested', 'seasons', 'years'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::with('songs')->where('slug', $slug)->first();

        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        if ($post == null) {
            return redirect(route('/'))->with('warning', 'Item do not exist!');
        }

        $openings = $post->songs->filter(function ($song) {
            return $song->type === 'OP';
        });

        $endings = $post->songs->filter(function ($song) {
            return $song->type === 'ED';
        });

        //$tags = $post->tagged;

        //dd($openings, $endings, $tags);

        $endings = $this->setScoreToSongVariants($endings, $score_format);

        $openings = $this->setScoreToSongVariants($openings, $score_format);

        //dd($openings,$endings);

        return view('public.posts.show', compact('post', 'openings', 'endings', 'score_format'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {}

    public function openings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();

        //dd($currentSeason,$currentYear);

        if (!$currentSeason && !$currentYear) {
            return redirect('/')->with('danger', 'Seasonal is not configured');
        }

        $season_id = $currentSeason->id;
        $year_id = $currentYear->id;
        $type = 'OP';
        $status = 'published';

        $song_variants = SongVariant::with(['song', 'song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereHas('song', function ($query) use ($type) {
                $query->when($type, function ($query, $type) {
                    $query->where('type', $type);
                });
            })
            ->when($currentSeason, function ($query) use ($season_id) {
                $query->where('season_id', $season_id);
            })
            ->when($currentYear, function ($query) use ($year_id) {
                $query->where('year_id', $year_id);
            })
            ->get();

        $song_variants = $this->setScoreOnlyVariants($song_variants, $score_format);

        return view('public.variants.seasonal', compact('song_variants', 'score_format', 'currentSeason', 'currentYear'));
    }
    public function endings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();

        //dd($currentSeason,$currentYear);

        if (!$currentSeason && !$currentYear) {
            return redirect('/')->with('danger', 'Seasonal is not configured');
        }

        $season_id = $currentSeason->id;
        $year_id = $currentYear->id;
        $type = 'ED';
        $status = 'published';

        $song_variants = SongVariant::with(['song', 'song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereHas('song', function ($query) use ($type) {
                $query->when($type, function ($query, $type) {
                    $query->where('type', $type);
                });
            })
            ->when($currentSeason, function ($query) use ($season_id) {
                $query->where('season_id', $season_id);
            })
            ->when($currentYear, function ($query) use ($year_id) {
                $query->where('year_id', $year_id);
            })
            ->get();

        $song_variants = $this->setScoreOnlyVariants($song_variants, $score_format);

        return view('public.variants.seasonal', compact('song_variants', 'score_format', 'currentSeason', 'currentYear'));
    }

    //public seasrch posts
    public function themes(Request $request)
    {
        $user = Auth::check() ? Auth::user() : null;


        $type = $request->type;
        $sort = $request->sort;
        $name = $request->name;
        $season = Season::where('name', $request->season)->first();
        $year = Year::where('name', $request->year)->first();

        $requested = new stdClass;
        $requested->type = $type;

        $requested->sort = $sort;
        $requested->name = $name;

        $requested->year = $request->year;
        $requested->season = $request->season;

        $years = Year::all();
        $seasons = Season::all();

        $types = $this->filterTypesSortChar()['types'];
        $sortMethods = $this->filterTypesSortChar()['sortMethods'];

        $song_variants = null;

        $song_variants = SongVariant::with(['song'])
            #SONG QUERY
            ->whereHas('song', function ($query) use ($type) {
                $query->when($type, function ($query, $type) {
                    $query->where('type', $type);
                });
            })
            #POST QUERY
            ->whereHas('song.post', function ($query) use ($name, $season, $year) {
                $query->where('status', 'published')
                    ->when($name, function ($query, $name) {
                        $query->where('title', 'LIKE', "%$name%");
                    })
                    ->when($season, function ($query, $season) {
                        $query->where('season_id', $season->id);
                    })
                    ->when($year, function ($query, $year) {
                        $query->where('year_id', $year->id);
                    });
            })
            #SONG VARIANT QUERY
            ->get();

        $song_variants = $this->setScoreOnlyVariants($song_variants, $user);
        $song_variants = $this->sort_variants($sort, $song_variants);
        $song_variants = $this->paginate($song_variants);

        //dd($song_variants);

        if ($request->ajax()) {
            $view = view('layouts.variant.cards', compact('song_variants'))->render();
            return response()->json(['html' => $view, "lastPage" => $song_variants->lastPage()]);
        }

        return view('public.variants.filter', compact('seasons', 'years', 'requested', 'sortMethods', 'types'));
    }

    public function setScoreOnlyVariants($variants, $user = null)
    {
        $variants->each(function ($variant) use ($user) {
            $variant->userScore = null;
            $factor = 1;
            $isDecimalFormat = false; // Determina si el formato permite decimales

            if ($user) {
                switch ($user->score_format) {
                    case 'POINT_100':
                        $factor = 1;
                        break;
                    case 'POINT_10_DECIMAL':
                        $factor = 0.1;
                        $isDecimalFormat = true;
                        break;
                    case 'POINT_10':
                        $factor = 1 / 10;
                        break;
                    case 'POINT_5':
                        $factor = 1 / 20;
                        $isDecimalFormat = true;
                        break;
                    default:
                        $factor = 1;
                        break;
                }

                if ($userRating = $this->getUserRating($variant->id, $user->id)) {
                    $variant->userScore = $isDecimalFormat
                        ? round($userRating->rating * $factor, 1) // Conserva 1 decimal
                        : (int) round($userRating->rating * $factor); // Fuerza entero
                }
            }

            $variant->score = $isDecimalFormat
                ? round($variant->averageRating * $factor, 1) // Conserva 1 decimal
                : (int) round($variant->averageRating * $factor); // Fuerza entero
        });

        return $variants;
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
                $song_variants = $song_variants->sortBy(function ($song_variant) {
                    return $song_variant->song->post->title;
                });
                return $song_variants;
                break;
        }
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

    public function getUserRating($song_variant_id, $user_id)
    {
        $user_rating = DB::table('ratings')
            ->where('rateable_type', SongVariant::class)
            ->where('rateable_id', $song_variant_id)
            ->where('user_id', $user_id)
            ->first(['rating']);

        return $user_rating;
    }
}
