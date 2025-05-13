<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Song;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function songsFilter(Request $request, $id)
    {

        $studio = Studio::findOrFail($id);

        $user = Auth::check() ? Auth::user() : null;
        $status = true;
        $type = $request->type;
        $sort = $request->sort;
        $name = $request->name;
        $year_id = $request->year_id; //this receive an ID, not a name
        $season_id = $request->season_id; //this receive an ID, not a name

        $songs = Song::whereHas('post', function ($query) use ($name, $status, $studio) {
            $query->where('status', $status)
                ->when($name, function ($query) use ($name) {
                    $query->where('title', 'like', '%' . $name . '%');
                })
                ->whereHas('studios', function ($query) use ($studio) {
                    $query->where('studios.id', $studio->id);
                });
        })
            ->when($year_id, function ($query) use ($year_id) {
                $query->where('year_id', $year_id);
            })
            ->when($season_id, function ($query) use ($season_id) {
                $query->where('season_id', $season_id);
            })

            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->get();

        $songs = $this->setScoreSongs($songs, $user);
        $songs = $this->sortSongs($sort, $songs);
        $songs = $this->paginate($songs, 15);

        return response()->json([
            'html' => view('partials.songs.cards-v2', compact('songs'))->render(),
            'songs' => $songs
        ]);
    }

    public function postsFilter(Request $request, $id)
    {

        $studio = Studio::findOrFail($id);

        //$user = Auth::check() ? Auth::user() : null;
        $status = true;
        $format_id = $request->format_id;
        $sort = $request->sort;
        $name = $request->name;
        $year_id = $request->year_id; //this receive an ID, not a name
        $season_id = $request->season_id; //this receive an ID, not a name

        $posts = Post::where('status', $status)
            ->whereHas('studios', function ($query) use ($studio) {
                $query->where('studios.id', $studio->id);
            })
            ->when($name, function ($query) use ($name) {
                $query->where('title', 'like', '%' . $name . '%');
            })
            ->when($year_id, function ($query) use ($year_id) {
                $query->where('year_id', $year_id);
            })
            ->when($season_id, function ($query) use ($season_id) {
                $query->where('season_id', $season_id);
            })
            ->when($format_id, function ($query) use ($format_id) {
                $query->where('format_id', $format_id);
            })
            ->get();

        //$songs = $this->setScoreSongs($songs, $user);
        //$songs = $this->sortSongs($sort, $songs);
        $posts = $this->paginate($posts, 15);

        return response()->json([
            'html' => view('partials.posts.cards-v2', compact('posts'))->render(),
            'posts' => $posts
        ]);
    }

    public function filter(Request $request)
    {
        $name = $request->name;
        $studios = Studio::when($name, function ($query, $name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        })
            ->get();

        $studios = $studios->sortBy(function ($studio) {
            return $studio->name;
        });

        $studios = $this->paginate($studios, 15);

        return response()->json([
            'studios' => $studios,
            'html' => view('partials.studios.cards-v2', compact('studios'))->render(),
        ]);
    }

    public function paginate($collection, $perPage = 18, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $collection instanceof Collection ? $collection : Collection::make($collection);
        $collection = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $collection;
    }

    public function getUserRating(int $song_id, int $user_id)
    {
        return DB::table('ratings')
            ->where('rateable_type', Song::class)
            ->where('rateable_id', $song_id)
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

    public function sortSongs($sort, $songs)
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

    public function setScoreSongs($songs, $user = null)
    {
        $songs->each(function ($song) use ($user) {

            #Inizialided attributes
            $song->formattedScore = null;
            $song->rawScore = null;
            $song->scoreString = null;

            $factor = 1;
            $isDecimalFormat = false;
            $denominator = 100; // Por defecto para POINT_100

            if ($user) {
                #Inizialided attributes
                $song->formattedUserScore = null;
                $song->rawUserScore = null;

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

                if ($userRating = $this->getUserRating($song->id, $user->id)) {
                    $song->formattedUserScore = $isDecimalFormat
                        ? round($userRating->rating * $factor, 1)
                        : (int) round($userRating->rating * $factor);

                    $song->rawUserScore = round($userRating->rating);
                }
            }

            $song->rawScore = round($song->averageRating, 1);

            $song->formattedScore = $isDecimalFormat
                ? round($song->averageRating * $factor, 1)
                : (int) round($song->averageRating * $factor);

            // Agregar la propiedad scoreString formateada
            $song->scoreString = $this->formatScoreString(
                $song->formattedScore,
                $user->score_format ?? 'POINT_100',
                $denominator
            );
        });

        return $songs;
    }
}
