<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Song;
use App\Models\Year;
use stdClass;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $artists = Artist::all()->sortBy('name');
        $characters = range('A', 'Z');
        return view('public.artists.index', compact('artists', 'characters'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  mixed  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $user = Auth::check() ? Auth::user() : null;
        //$tags = Tag::all();
        $type = $request->type;
        $sort = $request->sort;
        $name = $request->name;
        $year = Year::where('name', $request->year)->first();
        $season = Season::where('name', $request->season)->first();

        $requested = new stdClass;
        $requested->type = $type;
        $requested->sort = $sort;
        $requested->name = $name;
        $requested->year = $request->year;
        $requested->season = $request->season;

        $years = Year::all();
        $seasons = Season::all();

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

        $artist = Artist::where('slug', $slug)->first();

        $song_variants = Song::whereHas('artists', function ($query) use ($artist) {
            $query->where('artists.id', $artist->id);
        })
            ->when($year, function ($query) use ($year) {
                $query->where('year_id', $year->id);
            })
            ->when($season, function ($query) use ($season) {
                $query->where('season_id', $season->id);
            })
            ->whereHas('post', function ($query) use ($name) {
                $query->where('status', 'published')
                    ->when($name, function ($query) use ($name) {
                        $query->where('title', 'LIKE', "%{$name}%");
                    });
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->get()
            ->flatMap(function ($song) {
                return $song->songVariants;
            });

        //dd($query);

        //$songs = $this->setScore($query, $score_format);
        //$songs = $this->sort($sort, $songs);

        $song_variants = $this->setScoreOnlyVariants($song_variants, $user);
        $song_variants = $this->sort_variants($sort, $song_variants);
        $song_variants = $this->paginate($song_variants, 24)->withQueryString();

        //dd($song_variants);
        if ($request->ajax()) {

            $view = view('layouts.variant.cards', compact('song_variants'))->render();
            return response()->json(['html' => $view, "lastPage" => $song_variants->lastPage()]);
        }

        return view('public.variants.filter', compact('artist', 'seasons', 'years', 'requested', 'sortMethods', 'types'));
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
