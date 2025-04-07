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
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $user = Auth::check() ? Auth::User() : null;

        $post = Post::where('slug', $anime_slug)->firstOrFail();

        $song = Song::where('slug', $song_suffix)
            ->where('post_id', $post->id)
            ->firstOrFail();

        $song_variant = SongVariant::where('version_number', $variant_version_number)
            ->where('song_id', $song->id)
            ->with('reactionsCounter')
            ->firstOrFail();

        //dd($song_variant);
        $song_variant->score = 0;
        if ($song_variant == null) {
            return redirect(route('/'))->with('warning', 'Item no exist!');
        }

        if ($song_variant->song->post->status == 'stagged') {
            return redirect(route('/'))->with('warning', 'Paused post!');
        }

        $comments = $song_variant->comments;
        //dd($comments[0]->user);
        $factor = 1;
        if ($user) {

            $userRating = $this->getUserRating($song_variant->id, $user->id);

            if ($userRating) {

                switch ($user->score_format) {
                    case 'POINT_100':
                        $factor = 1;
                        $userRating->formatRating = round($userRating->rating);
                        break;

                    case 'POINT_10_DECIMAL':
                        $factor = 0.1;
                        $userRating->formatRating = round($userRating->rating / 10, 1);
                        break;

                    case 'POINT_10':
                        $factor = 1 / 10;
                        $userRating->formatRating = round($userRating->rating / 10);
                        break;

                    case 'POINT_5':
                        $factor = 1 / 20;
                        //Divide the score in segments of [20, 40, 60, 80, 100]
                        $userRating->formatRating = (int) max(20, min(100, ceil($userRating->rating / 20) * 20));
                        break;

                    default:
                        $factor = 1;
                        $userRating->formatRating = round($userRating->rating);
                        break;
                }
            }
        } else {
            $song_variant->score = round($song_variant->averageRating * $factor, 1);
            $userRating = null;
        }

        $song_variant = $this->setScoreOnlyOneVariant($song_variant, $user);

        //dd($song_variant);

        $song_variant->incrementViews();

        return view('public.variants.show', compact('song_variant', 'comments', 'userRating'));
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

    public function getUserRating($song_variant_id, $user_id)
    {
        $userRating = DB::table('ratings')
            ->where('rateable_type', SongVariant::class)
            ->where('rateable_id', $song_variant_id)
            ->where('user_id', $user_id)
            ->first(['rating']);

        return $userRating;
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
        /*  $type_OP = 'OP'; */
        /* $type_ED = 'ED'; */
        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();
        /*  $user = Auth::check() ? Auth::User() : null; */

        /* $openings = SongVariant::with(['song'])
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
            ->get(); */

        /* $endings = SongVariant::with(['song'])
            #SONG QUERY
            ->whereHas('song', function ($query) use ($type_ED) {
                $query->when($type_ED, function ($query, $type) {
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
            ->get(); */

        //dd($openings, $endings);

        //$openings = $this->setScoreOnlyVariants($openings, $user);
        //$endings = $this->setScoreOnlyVariants($endings, $user);

        /* dd($openings[5]->favoritesCount); */

        return view('public.variants.seasonal', compact('currentSeason', 'currentYear'/* , 'openings', 'endings' */));
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

    #New
    public function setScoreOnlyOneVariant($variant, $user = null)
    {
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


        return $variant;
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

    public function ranking()
    {
        $user = Auth::check() ? Auth::user() : null;
        $status = true;

        $openings = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereHas('song', function ($query) {
                $query->where('type', 'OP');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(100);

        $openings = $this->setScoreOnlyVariants($openings, $user);

        //dd($openings[0]);

        return view('public.variants.ranking');
    }

    public function seasonalRanking()
    {

        $user = Auth::check() ? Auth::user() : null;

        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();

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

            $this->setScoreOnlyVariants($openings, $user);
            $this->setScoreOnlyVariants($endings, $user);

            return view('public.variants.ranking', compact('openings', 'endings'));
        } else {
            $openings = SongVariant::with(['song'])
                ->whereHas('song.post', function ($query) use ($currentSeason, $currentYear) {
                    $query->where('status', 'published')
                        ->where('season_id', $currentSeason->id)
                        ->where('year_id', $currentYear->id);
                })
                ->whereHas('song', function ($query) {
                    $query->where('type', 'OP');
                })
                ->get()
                ->sortByDesc('averageRating')
                ->take(100);

            $endings = SongVariant::with(['song'])
                ->whereHas('song.post', function ($query) use ($currentSeason, $currentYear) {
                    $query->where('status', 'published')
                        ->where('season_id', $currentSeason->id)
                        ->where('year_id', $currentYear->id);
                })
                ->whereHas('song', function ($query) {
                    $query->where('type', 'ED');
                })
                ->get()
                ->sortByDesc('averageRating')
                ->take(100);

            $this->setScoreOnlyVariants($openings, $user);
            $this->setScoreOnlyVariants($endings, $user);

            return view('public.variants.ranking', compact('openings', 'endings', 'currentSeason'));
        }
    }
    public function globalRanking()
    {
        $user = Auth::check() ? Auth::user() : null;
        $status = true;

        $openings = SongVariant::with(['song.post'])
            ->whereHas('song.post', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->whereHas('song', function ($query) {
                $query->where('type', 'OP');
            })
            ->get()
            ->sortByDesc('averageRating')
            ->take(100);

        //$openings = $getOpenings->sortByDesc('averageRating')->take(100);

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
            ->take(100);

        //$endings = $getEndings->sortByDesc('averageRating')->take(100);

        $endings = $this->setScoreOnlyVariants($endings, $user);

        return view('public.variants.ranking', compact('openings', 'endings'));
    }

    public function paginate($collection, $perPage = 18, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $collection instanceof Collection ? $collection : Collection::make($collection);
        $collection = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $collection;
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
