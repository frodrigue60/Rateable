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
        $status = true;

        $recently = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->get()
            ->sortByDesc('created_at')
            ->take(25);
        $popular = $this->setScoreOnlyVariants($recently, $user);


        $popular = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->get()
            ->sortByDesc('likesCount')
            ->take(15);

        $popular = $this->setScoreOnlyVariants($popular, $user);

        $viewed = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->get()
            ->sortByDesc('views')
            ->take(15);

        $viewed = $this->setScoreOnlyVariants($viewed, $user);

        $openings = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereHas('song', function ($query) {
                $query->where('type', 'OP');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(5);

        $openings = $this->setScoreOnlyVariants($openings, $user);

        $endings = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
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
        $status = true;

        $posts = Post::where('status', $status)
            ->when($season, function ($query, $season) {
                $query->where('season_id', $season->id);
            })
            ->when($year, function ($query, $year) {
                $query->where('year_id', $year->id);
            })
            ->when($name, function ($query, $name) {
                $query->where('title', 'LIKE', '%'.$name.'%');
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
        $post = Post::with('songs.songVariants')->where('slug', $slug)->first();

        $user = Auth::check() ? Auth::User() : null;

        if ($post == null) {
            return redirect(route('/'))->with('warning', 'Item do not exist!');
        }

        $openings = $post->songs->filter(function ($song) {
            return $song->type === 'OP';
        });

        $openings = $this->setScoreToSongVariants($openings, $user);

        $endings = $post->songs->filter(function ($song) {
            return $song->type === 'ED';
        });

        $endings = $this->setScoreToSongVariants($endings, $user);

        return view('public.posts.show', compact('post', 'openings', 'endings'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {}

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
        $status = true;

        $song_variants = SongVariant::with(['song'])
            #SONG QUERY
            ->whereHas('song', function ($query) use ($type) {
                $query->when($type, function ($query, $type) {
                    $query->where('type', $type);
                });
            })
            #POST QUERY
            ->whereHas('song.post', function ($query) use ($name, $season, $year, $status) {
                $query->where('status', $status)
                    ->when($name, function ($query, $name) {
                        $query->where('title', 'LIKE', '%'.$name.'%');
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
            $isDecimalFormat = false;
            $denominator = 100; // Por defecto para POINT_100

            if ($user) {
                switch ($user->score_format) {
                    case 'POINT_100':
                        $factor = 1;
                        $denominator = 100;
                        break;
                    case 'POINT_10_DECIMAL':
                        $factor = 0.1;
                        $denominator = 10;
                        $isDecimalFormat = true;
                        break;
                    case 'POINT_10':
                        $factor = 1 / 10;
                        $denominator = 10;
                        break;
                    case 'POINT_5':
                        $factor = 1 / 20;
                        $denominator = 5;
                        $isDecimalFormat = true;
                        break;
                }

                if ($userRating = $this->getUserRating($variant->id, $user->id)) {
                    $variant->userScore = $isDecimalFormat
                        ? round($userRating->rating * $factor, 1)
                        : (int) round($userRating->rating * $factor);
                }
            }

            $variant->score = $isDecimalFormat
                ? round($variant->averageRating * $factor, 1)
                : (int) round($variant->averageRating * $factor);

            // Agregar la propiedad scoreString formateada
            $variant->scoreString = $this->formatScoreString(
                $variant->score,
                $user->score_format ?? 'POINT_100',
                $denominator
            );
        });

        return $variants;
    }

    public function setScoreToSongVariants($songsArray, $user = null)
    {
        $songsArray->each(function ($song) use ($user) {
            $song->songVariants->each(function ($variant) use ($user) {
                $variant->userScore = null;
                $factor = 1;
                $isDecimalFormat = false;
                $denominator = 100; // Por defecto para POINT_100

                if ($user) {
                    switch ($user->score_format) {
                        case 'POINT_100':
                            $factor = 1;
                            $denominator = 100;
                            break;
                        case 'POINT_10_DECIMAL':
                            $factor = 0.1;
                            $denominator = 10;
                            $isDecimalFormat = true;
                            break;
                        case 'POINT_10':
                            $factor = 1 / 10;
                            $denominator = 10;
                            break;
                        case 'POINT_5':
                            $factor = 1 / 20;
                            $denominator = 5;
                            $isDecimalFormat = true;
                            break;
                    }

                    if ($userRating = $this->getUserRating($variant->id, $user->id)) {
                        $variant->userScore = $isDecimalFormat
                            ? round($userRating->rating * $factor, 1)
                            : (int) round($userRating->rating * $factor);
                    }
                }

                $variant->score = $isDecimalFormat
                    ? round($variant->averageRating * $factor, 1)
                    : (int) round($variant->averageRating * $factor);

                // Agregar la propiedad scoreString formateada
                $variant->scoreString = $this->formatScoreString(
                    $variant->score,
                    $user->score_format ?? 'POINT_100',
                    $denominator
                );
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
            ['name' => 'Ending', 'value' => 'ED'],
            ['name' => 'Insert', 'value' => 'INS'],
            ['name' => 'Other', 'value' => 'OTH'],
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

    public function getUserRating(int $song_variant_id, int $user_id)
    {
        return DB::table('ratings')
            ->where('rateable_type', SongVariant::class)
            ->where('rateable_id', $song_variant_id)
            ->where('user_id', $user_id)
            ->first(['rating']);
    }

    protected function formatScoreString($score, $format, $denominator)
    {
        switch ($format) {
            case 'POINT_100':
                return $score . '/' . $denominator;
            case 'POINT_10_DECIMAL':
                return number_format($score, 1) . '/' . $denominator;
            case 'POINT_10':
                return $score . '/' . $denominator;
            case 'POINT_5':
                return number_format($score, 1) . '/' . $denominator;
            default:
                return $score . '/' . $denominator;
        }
    }
}
