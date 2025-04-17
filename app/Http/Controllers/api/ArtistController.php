<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Artist;
use Illuminate\Support\Facades\Auth;
use App\Models\Year;
use App\Models\Season;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\SongVariant;

class ArtistController extends Controller
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

    public function filter(Request $request, $id)
    {

        $artist = Artist::where('id', $id)->first();

        $user = Auth::check() ? Auth::user() : null;
        $status = true;
        $type = $request->type;
        $sort = $request->sort;
        $name = $request->name;
        $year = $request->year;//this receive an ID, not a name
        $season = $request->season;//this receive an ID, not a name

        $song_variants = Song::whereHas('artists', function ($query) use ($artist) {
            $query->where('artists.id', $artist->id);
        })
            ->when($year, function ($query) use ($year) {
                $query->where('year_id', $year);
            })
            ->when($season, function ($query) use ($season) {
                $query->where('season_id', $season);
            })
            ->whereHas('post', function ($query) use ($name, $status) {
                $query->where('status', $status)
                    ->when($name, function ($query) use ($name) {
                        $query->where('title', 'like', '%' . $name . '%');
                    });
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->get()
            ->flatMap(function ($song) {
                return $song->songVariants;
            });

        $song_variants = $this->setScoreOnlyVariants($song_variants, $user);
        $song_variants = $this->sort_variants($sort, $song_variants);
        $song_variants = $this->paginate($song_variants, 24)->withQueryString();

        $view = view('partials.variants.cards', compact('song_variants'))->render();

        return response()->json([
            'html' => $view, 
            "lastPage" => $song_variants->lastPage(),/* "artist" => $artist */  
             
        ]);
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

    public function paginate($collection, $perPage = 18, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $collection instanceof Collection ? $collection : Collection::make($collection);
        $collection = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $collection;
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
