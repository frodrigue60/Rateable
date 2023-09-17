<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Song;
use stdClass;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Conner\Tagging\Model\Tag;


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
     * @param  mixed  $name_slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $name_slug)
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            $user = Auth::user();
        } else {
            $score_format = null;
        }
        $tags = Tag::all();
        $type = $request->type;
        $sort = $request->sort;
        $char = $request->char;

        $requested = new stdClass;
        $requested->type = $type;
        $requested->sort = $sort;
        $requested->char = $char;
        $requested->year = $request->year;
        $requested->season = $request->season;

        $years = $this->SeasonsYears($tags)['years'];
        $seasons = $this->SeasonsYears($tags)['seasons'];

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

        $artist = Artist::where('name_slug', $name_slug)->where('id', $id)->first();

        if ($request->year != null || $request->season != null) {
            if ($request->year != null && $request->season != null) {
                $tag = $request->season . ' ' . $request->year;
            } else {
                $tag = DB::table('tagging_tags')
                    ->where(function ($query) use ($request) {
                        if ($request->year != null) {
                            $query->where('name', 'LIKE', '%' . $request->year . '%');
                        } else {
                            $query->where('name', 'LIKE', '%' . $request->season . '%');
                        }
                    })
                    ->limit(4)
                    ->get()
                    ->pluck('name')
                    ->toArray();
            }
            if ($type != null) {
                if ($char != null) {
                    $songs = Song::with(['post'])->where('artist_id', '=', $artist->id)
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })
                        ->where('type', $type)
                        ->get();
                } else {
                    $songs = Song::with(['post'])->where('artist_id', '=', $artist->id)
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })
                        ->where('type', $type)
                        ->get();
                }
            } else {
                if ($char != null) {
                    $songs = Song::with(['post'])->where('artist_id', '=', $artist->id)
                        ->withAnyTag($tag)
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })->get();
                } else {
                    //dd($request->all());
                    $songs = Song::with(['post'])->where('artist_id', '=', $artist->id)
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
                    $songs = Song::with(['post'])->where('artist_id', '=', $artist->id)
                        ->whereHas('post', function ($query) use ($char) {
                            $query->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })
                        ->where('type', $type)
                        ->get();
                } else {
                    $songs = Song::with(['post'])->where('artist_id', '=', $artist->id)
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })->where('type', $type)->get();
                }
            } else {
                if ($char != null) {
                    $songs = Song::with(['post'])->where('artist_id', '=', $artist->id)
                        ->whereHas('post', function ($query) use ($char) {
                            $query
                                ->where('status', 'published')
                                ->where('title', 'LIKE', "{$char}%");
                        })->get();
                } else {

                    $songs = Song::with(['post'])->where('artist_id', '=', $artist->id)
                        ->whereHas('post', function ($query) {
                            $query->where('status', 'published');
                        })->get();
                }
            }
        }

        $songs = $this->setScore($songs, $score_format);
        $songs = $this->sort($sort, $songs);
        $songs = $this->paginate($songs, 24)->withQueryString();

        if ($request->ajax()) {
            //error_log('new ajax request');
            $view = view('layouts.songs-cards', compact('songs'))->render();
            return response()->json(['html' => $view, "lastPage" => $songs->lastPage()]);
        }

        return view('public.songs.filter', compact('artist', 'seasons', 'years', 'requested', 'sortMethods', 'types', 'characters'));
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
}
