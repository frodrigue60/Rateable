<?php

namespace App\Http\Controllers;

use App\Models\SongVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\Song;
use App\Models\Reaction;
use App\Models\Season;
use App\Models\Year;
use App\Models\Favorite;

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
    public function show($anime_slug, $song_suffix, $variant_version_number)
    {
        $score = null;
        $user_rate = null;

        $post = Post::where('slug', $anime_slug)->firstOrFail();

        $song = Song::where('slug', $song_suffix)
            ->where('post_id', $post->id)
            ->firstOrFail();

        $song_variant = SongVariant::where('version_number', $variant_version_number)
            ->where('song_id', $song->id)
            ->with('reactionsCounter')
            ->firstOrFail();

        //dd($song_variant);

        if ($song_variant == null) {
            return redirect(route('/'))->with('warning', 'Item no exist!');
        }

        if ($song_variant->song->post->status == 'stagged') {
            return redirect(route('/'))->with('warning', 'Paused post!');
        }

        $comments = $song_variant->comments;
        //dd($comments);

        if (Auth::check() && $song_variant->averageRating) {

            $user_rate = $this->userRate($song_variant->id, Auth::user()->id);

            if ($user_rate) {
                switch (Auth::user()->score_format) {
                    case 'POINT_100':
                        $score = round($song_variant->averageRating);
                        $user_rate->format_rating = round($user_rate->rating);
                        break;

                    case 'POINT_10_DECIMAL':
                        $score = round($song_variant->averageRating / 10, 1);
                        $user_rate->format_rating = round($user_rate->rating / 10, 1);
                        break;

                    case 'POINT_10':
                        $score = round($song_variant->averageRating / 10);
                        $user_rate->format_rating = round($user_rate->rating / 10);
                        break;

                    case 'POINT_5':
                        $score = round($song_variant->averageRating / 20);
                        //Divide the score in segments of [20, 40, 60, 80, 100]
                        $user_rate->format_rating = max(20, min(100, ceil($user_rate->rating / 20) * 20));
                        break;

                    default:
                        $score = round($song_variant->averageRating / 10);
                        $user_rate->format_rating = max(20, min(100, ceil($user_rate->rating / 20) * 20));
                        break;
                }
            }else{
                $user_rate = '';
            }
        } else {
            $score = round($song_variant->averageRating);
        }

        //dd($song_variant->avgScore);

        $song_variant->incrementViews();

        return view('public.variants.show', compact('song_variant', 'score', 'comments', 'user_rate'));
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

    public function rate(Request $request, $variant_id)
    {
        //dd($request->all());
        if (Auth::check()) {

            $songVariant = SongVariant::find($variant_id);

            $score_format = Auth::user()->score_format;

            $validator = Validator::make($request->all(), [
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

            if ($score_format === 'POINT_5') {
                // Ajustar el score según las reglas específicas para POINT_5
                $score = max(20, min(100, ceil($score / 20) * 20));
            } else {
                // Ajustar el score según las reglas comunes para POINT_100, POINT_10_DECIMAL y POINT_10
                $score = max(1, min(100, ($score_format === 'POINT_10_DECIMAL') ? round($score * 10) : round($score)));
            }

            // Validar el rango del score
            if ($score >= 1 && $score <= 100) {
                // Utilizar el score ajustado
                $songVariant->rateOnce($score, Auth::User()->id);
                return redirect()->back()->with('success', 'Post rated Successfully');
            } else {
                return redirect()->back()->with('warning', 'Only values between 1 and 100');
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function userRate($song_variant_id, $user_id)
    {
        $user_rate = DB::table('ratings')
            ->where('rateable_type', SongVariant::class)
            ->where('rateable_id', $song_variant_id)
            ->where('user_id', $user_id)
            ->first(['rating']);

        return $user_rate;
    }

    public function like($songVariant_id)
    {
        $songVariant = SongVariant::find($songVariant_id);
        $this->handleReaction($songVariant, 1); // 1 para like
        $songVariant->updateReactionCounters(); // Actualiza los contadores manualmente
        return redirect()->back(); // Redirige de vuelta a la página anterior
    }

    // Método para dislike
    public function dislike($songVariant_id)
    {
        $songVariant = SongVariant::find($songVariant_id);
        $this->handleReaction($songVariant, -1); // -1 para dislike
        $songVariant->updateReactionCounters(); // Actualiza los contadores manualmente
        return redirect()->back(); // Redirige de vuelta a la página anterior
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

    public function seasonal(Request $request)
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $type_OP = 'OP';
        $type_ED = 'ED';
        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();

        $openings = SongVariant::with(['song'])
            #SONG QUERY
            ->whereHas('song', function ($query) use ($type_OP) {
                $query->when($type_OP, function ($query, $type) {
                    $query->where('type', $type);
                });
            })
            ->whereHas('song.post', function ($query) use ($currentSeason, $currentYear) {
                #POST QUERY
                $query->where('status', 'published')
                    ->when($currentSeason, function ($query, $currentSeason) {
                        $query->where('season_id', $currentSeason->id);
                    })
                    ->when($currentYear, function ($query, $currentYear) {
                        $query->where('year_id', $currentYear->id);
                    });
            })
            #SONG VARIANT QUERY
            ->get();

        $endings = SongVariant::with(['song'])
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
        //dd($openings, $endings);

        //$openings = $this->setScoreOnlyVariants($openings, $score_format);
        //$endings = $this->setScoreOnlyVariants($endings, $score_format);

        return view('public.variants.seasonal', compact('currentSeason', 'currentYear'));
    }

    public function setScoreOnlyVariants($variantsArray, $score_format)
    {
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

    public function toggleFavorite($songVariant_id)
    {

        if (!Auth::check()) {
            return redirect()->back()->with('warning', 'Please login');
        }

        $songVariant = SongVariant::find($songVariant_id);
        $user = Auth::user();

        // Verificar si el post ya está en favoritos
        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $songVariant->id)
            ->where('favoritable_type', SongVariant::class)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Theme removed to favorites');
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_id' => $songVariant->id,
                'favoritable_type' => SongVariant::class,
            ]);
            return redirect()->back()->with('success', 'Theme added to favorites');
        }
    }
}
