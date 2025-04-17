<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Season;
use App\Models\Year;
use App\Models\Reaction;
use App\Models\Favorite;
use Illuminate\Support\Facades\Validator;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => 'mathod: index']);
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

    public function like(Request $request)
    {
        $song = Song::find($request->song_id);
        $this->handleReaction($song, 1); // 1 para like
        $song->updateReactionCounters(); // Actualiza los contadores manualmente

        return response()->json([
            'success' => true,
            'message' => 'Liked',
            'likesCount' => $song->likesCount,
            'dislikesCount' => $song->dislikesCount,
        ], 200);
    }

    // Método para dislike
    public function dislike(Request $request)
    {
        $song = Song::find($request->song_id);
        $this->handleReaction($song, -1); // -1 para dislike
        $song->updateReactionCounters(); // Actualiza los contadores manualmente

        return response()->json([
            'success' => true,
            'message' => 'disliked',
            'likesCount' => $song->likesCount,
            'dislikesCount' => $song->dislikesCount,
        ], 200);
    }
    // Método privado para manejar la reacción
    private function handleReaction($song, $type)
    {
        $user = Auth::user();

        // Buscar si ya existe una reacción del usuario para este post
        $reaction = Reaction::where('user_id', $user->id)
            ->where('reactable_id', $song->id)
            ->where('reactable_type', Song::class)
            ->first();

        if ($reaction) {
            if ($reaction->type === $type) {
                // Si la reacción es la misma, eliminarla (toggle)
                $reaction->delete();
            } else {
                // Si la reacción es diferente, actualizarla
                $reaction->update(['type' => $type]);
            }
        } else {
            // Si no existe una reacción, crear una nueva
            Reaction::create([
                'user_id' => $user->id,
                'reactable_id' => $song->id,
                'reactable_type' => Song::class,
                'type' => $type,
            ]);
        }
    }
    public function toggleFavorite(Request $request)
    {

        $song = Song::find($request->song_id);
        $user = Auth::user();

        // Verificar si el post ya está en favoritos
        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $song->id)
            ->where('favoritable_type', Song::class)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'message' => 'Removed from favorites',
                'favorite' => false,
            ], 200);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_id' => $song->id,
                'favoritable_type' => Song::class,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Added to favorites',
                'favorite' => true,
            ], 200);
        }
    }
    public function rate(Request $request, $song_id)
    {

        $user = Auth::check() ? Auth::user() : null;
        $song = Song::find($song_id);

        $factor = 1;
        $isDecimalFormat = false;

        $score_format = $user->score_format;

        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric|max:100'
        ]);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();
            return response()->json([
                'success' => false,
                'message' => $messageBag,
                'score' => $request->score,
            ], 200);
        } else {
            $score = $request->score;
        }

        switch ($score_format) {
            case 'POINT_5':
                $score = max(20, min(100, ceil($score / 20) * 20));
                $factor = 1 / 20;
                break;

            case 'POINT_10':
                $score = round($score * 10);
                $factor = 1 / 10;
                break;

            case 'POINT_10_DECIMAL':
                $score = round($score * 10, 1);
                $factor = 0.1;
                break;

            case 'POINT_100':
                $score = round($score);
                $factor = 1;
                break;
            default:
                $score = round($score);
                $factor = 1;
                break;
        }

        // Utilizar el score ajustado
        $song->rateOnce($score, $user->id);
        $average = $song->averageRating;
        $average = round($average * $factor, 1);

        return response()->json([
            'success' => true,
            'message' => 'Rated sucessfully',
            'score' => $score,
            'average' => $average,
        ], 200);
    }

    public function filter(Request $request)
    {
        $user = Auth::check() ? Auth::user() : null;
        $type = $request->type;
        $sort = $request->sort;
        $name = $request->name;
        $season = $request->season;
        $year = $request->year;

        $posts = null;
        $status = true;
        $view = null;

        $posts = Post::with(['songs' => function ($query) use ($type) {
            // Filtra por 'type' SOLO si está en la request
            if ($type) {
                $query->where('type', $type); // OP, ED, etc.
            }
        }])
            ->when($name, function ($query, $name) {
                $query->where('title', 'LIKE', '%' . $name . '%');
            })
            ->when($season, function ($query, $season) {
                $query->where('season_id', $season);
            })
            ->when($year, function ($query, $year) {
                $query->where('year_id', $year);
            })
            ->get(); // O cualquier otra lógica para obtener los posts

        //$song_variants = $this->setScoreOnlyVariants($song_variants, $user);
        //$songs = $this->sortSongs($sort, $songs);
        $posts = $this->paginate($posts);

        $view = view('partials.posts.accordions', compact('posts'))->render();

        return response()->json([
            /* 'data' => $posts, */
            'html' => $view,
            "lastPage" => $posts->lastPage()
        ]);
    }

    public function seasonal(Request $request)
    {
        $status = true;
        $type = $request->type != null ? $request->type : 'OP';
        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();

        //$user = Auth::check() ? Auth::User() : null;

        $songs = Song::with(['post'])
            /* SONG QUERY */
            ->where('type', $type)
            ->whereHas('post', function ($query) use ($currentSeason, $currentYear, $status) {
                /* POST QUERY */
                $query->where('status', $status)
                    ->when($currentSeason, function ($query, $currentSeason) {
                        $query->where('season_id', $currentSeason->id);
                    })
                    ->when($currentYear, function ($query, $currentYear) {
                        $query->where('year_id', $currentYear->id);
                    });
            })
            /* SONG VARIANT QUERY */
            ->get();

        //$songs = $this->setScoreOnlyVariants($themes, $user);

        $data = [
            'songsRender' => view('partials.songs.cards', compact('songs'))->render()
        ];

        return response()->json($data);
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

    public function paginate($collection, $perPage = 18, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $collection instanceof Collection ? $collection : Collection::make($collection);
        $collection = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $collection;
    }
}
