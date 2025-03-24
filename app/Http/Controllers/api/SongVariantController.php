<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SongVariant;
use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\Season;
use Illuminate\Support\Facades\Auth;
use App\Models\Reaction;
use App\Models\Favorite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SongVariantController extends Controller
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
        $songVariant = SongVariant::find($request->songVariant_id);
        $this->handleReaction($songVariant, 1); // 1 para like
        $songVariant->updateReactionCounters(); // Actualiza los contadores manualmente

        return response()->json([
            'success' => true,
            'message' => 'Liked',
            'likesCount' => $songVariant->likesCount,
            'dislikesCount' => $songVariant->dislikesCount,
        ], 200);
    }

    // Método para dislike
    public function dislike(Request $request)
    {
        $songVariant = SongVariant::find($request->songVariant_id);
        $this->handleReaction($songVariant, -1); // -1 para dislike
        $songVariant->updateReactionCounters(); // Actualiza los contadores manualmente

        return response()->json([
            'success' => true,
            'message' => 'disliked',
            'likesCount' => $songVariant->likesCount,
            'dislikesCount' => $songVariant->dislikesCount,
        ], 200);
    }

    // Método privado para manejar la reacción
    private function handleReaction($songVariant, $type)
    {
        $user = Auth::user();

        // Buscar si ya existe una reacción del usuario para este post
        $reaction = Reaction::where('user_id', $user->id)
            ->where('reactable_id', $songVariant->id)
            ->where('reactable_type', SongVariant::class)
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
                'reactable_id' => $songVariant->id,
                'reactable_type' => SongVariant::class,
                'type' => $type,
            ]);
        }
    }

    public function seasonal()
    {
        $type_OP = 'OP';
        $type_ED = 'ED';
        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();

        $openings = SongVariant::with(['song.post'])
            #SONG QUERY
            ->whereHas('song', function ($query) use ($type_OP) {
                $query->when($type_OP, function ($query, $type) {
                    $query->where('type', $type);
                });
            })

            ->whereHas('song.post', function ($query) use ($currentSeason, $currentYear) {
                #POST QUERY
                $query->where('status', 'published')
                    /* ->when($char, function ($query, $char) {
                        $query->where('title', 'LIKE', "{$char}%");
                    }) */
                    ->when($currentSeason, function ($query, $currentSeason) {
                        $query->where('season_id', $currentSeason->id);
                    })
                    ->when($currentYear, function ($query, $currentYear) {
                        $query->where('year_id', $currentYear->id);
                    });
            })
            #SONG VARIANT QUERY
            ->get();

        $endings = SongVariant::with(['song.post'])
            #SONG QUERY
            ->whereHas('song', function ($query) use ($type_ED) {
                $query->when($type_ED, function ($query, $type) {
                    $query->where('type', $type);
                });
            })
            ->whereHas('song.post', function ($query) use ($currentSeason, $currentYear) {
                #POST QUERY
                $query->where('status', 'published')
                    /* ->when($char, function ($query, $char) {
                        $query->where('title', 'LIKE', "{$char}%");
                    }) */
                    ->when($currentSeason, function ($query, $currentSeason) {
                        $query->where('season_id', $currentSeason->id);
                    })
                    ->when($currentYear, function ($query, $currentYear) {
                        $query->where('year_id', $currentYear->id);
                    });
            })
            #SONG VARIANT QUERY
            ->get();

        $song_variants = $openings;
        $renderOps = view('layouts.variant.cards', compact('song_variants'))->render();

        $song_variants = $endings;
        $renderEds = view('layouts.variant.cards', compact('song_variants'))->render();

        $data = ['openings' => $renderOps, 'endings' => $renderEds];

        return response()->json($data);
    }

    public function toggleFavorite(Request $request)
    {

        $songVariant = SongVariant::find($request->songVariant_id);
        $user = Auth::user();

        // Verificar si el post ya está en favoritos
        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $songVariant->id)
            ->where('favoritable_type', SongVariant::class)
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
                'favoritable_id' => $songVariant->id,
                'favoritable_type' => SongVariant::class,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Added to favorites',
                'favorite' => true,
            ], 200);
        }
    }

    public function rate(Request $request, $variant_id)
    {
        $user = Auth::user();
        $songVariant = SongVariant::find($variant_id);

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
                break;

            case 'POINT_10':
                $score = round($score * 10);
                break;

            case 'POINT_10_DECIMAL':
                $score = round($score * 10, 1);
                break;

            case 'POINT_100':
                $score = round($score);
                break;
            default:
                $score = round($score);
                break;
        }

        // Utilizar el score ajustado
        $songVariant->rateOnce($score, $user->id);

        return response()->json([
            'success' => true,
            'message' => 'Rated sucessfully',
            'score' => $score,
            'scoreString' => $songVariant->scoreString,
        ], 200);
    }

    public function userRate($song_variant_id, $user_id)
    {
        return DB::table('ratings')
            ->where('rateable_type', SongVariant::class)
            ->where('rateable_id', $song_variant_id)
            ->where('user_id', $user_id)
            ->first(['rating']);
        //dd('rate');
    }
}
