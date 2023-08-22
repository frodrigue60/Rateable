<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Song;
use Conner\Tagging\Model\Tag;
use stdClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $score_formats = [
            ['name' => ' 100 Point (55/100)', 'value' => 'POINT_100'],
            ['name' => '10 Point Decimal (5.5/10)', 'value' => 'POINT_10_DECIMAL'],
            ['name' => '10 Point (5/10)', 'value' => 'POINT_10'],
            ['name' => '5 Star (3/5)', 'value' => 'POINT_5'],
        ];

        if (Auth::check()) {
            $user = Auth::user();
            return view('public.users.profile', compact('score_formats', 'user'));
        } else {
            return redirect()->route('/')->with('warning', 'Please login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
    public function userList(Request $request, $userId)
    {
        $user = User::find($userId);

        $score_format = $user->score_format;

        $tags = Tag::all();
        $tag = $request->tag;
        $filterBy = $request->filterBy;
        $type = $request->type;
        $sort = $request->sort;
        $char = $request->char;

        $requested = new stdClass;
        $requested->filterBy = $filterBy;
        $requested->type = $type;
        $requested->tag = $tag;
        $requested->sort = $sort;
        $requested->char = $char;

        $years = [];
        $seasons = [];

        for ($i = 1950; $i < 2050; $i++) {
            $years[] = ['name' => $i, 'value' => $i];
        }

        $seasons = [
            ['name' => 'SPRING', 'value' => 'SPRING'],
            ['name' => 'SUMMER', 'value' => 'SUMMER'],
            ['name' => 'FALL', 'value' => 'FALL'],
            ['name' => 'WINTER', 'value' => 'WINTER']
        ];

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

        switch ($filterBy) {
            case 'all':
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                }
                break;
            case 'rated':
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            /* ONLY RATED, HAS TYPE, HAS SEASON */
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->withAnyTag($tag)
                                ->with(['post' => function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                }])
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('likeCounter')
                                //->with('post')
                                ->get();
                                //dd($songs);
                        } else {
                            /* ONLY RATED, TYPE, SEASON */
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->withAnyTag($tag)
                                ->with(['post' => function ($query) {
                                    $query->where('status', 'published');
                                }])
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('likeCounter')
                                ->get();
                                
                        } else {
                                $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('likeCounter')
                                ->get();
                        } else {
                            /* ONLY RATED, TYPE */
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->with(['post' => function ($query) {
                                    $query->where('status', 'published');
                                }])
                                ->where('type', $type)
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('likeCounter')
                                ->get();

                            //dd($songs);
                        }
                    } else {
                        if ($char != null) {
                            /* ONLY RATED, CHAR SELECT */
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('likeCounter')
                                ->get();
                                //dd($songs);
                        } else {
                            /* ONLY RATED */
                            $songs = Song::select('songs.*', 'posts.title', 'posts.thumbnail', 'ratings.rating')
                                ->with(['post' => function ($query) {
                                    $query->where('status', 'published');
                                }])
                                ->join('ratings', 'songs.id', '=', 'ratings.rateable_id')
                                ->join('posts', 'songs.post_id', '=', 'posts.id')
                                ->where('ratings.user_id', '=', $user->id)
                                //->with('likeCounter')
                                ->get();
                            //dd($songs);
                        }
                    }
                }
                break;
            default:
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->withAnyTag($tag)
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) use ($char) {
                                    $query
                                        ->where('status', 'published')
                                        ->where('title', 'LIKE', "{$char}%");
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $songs = Song::with(['post'])
                                ->whereHas('post', function ($query) {
                                    $query->where('status', 'published');
                                })
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                }
                break;
        }

        $songs = $this->setScore($songs, $score_format);
        $songs = $this->sort($sort, $songs);

        $songs = $this->paginate($songs, 24)->withQueryString();
        //dd($songs);
        if ($request->ajax()) {
            //error_log('new ajax request');
            $view = view('public.songs.songs-cards', compact('songs'))->render();
            return response()->json(['html' => $view, "lastPage" => $songs->lastPage()]);
        }
        return view('public.songs.filter', compact('seasons','years', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
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
                return $songs;
                break;
        }
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

    public function uploadProfilePic(Request $request)
    {
        if ($request->hasFile('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return redirect(route('profile'))->with('status', $errors);
            }

            //$user_email = Auth::user()->email;
            $user_id = Auth::user()->id;
            $old_user_image = Auth::user()->image;

            $file_type = $request->image->extension();
            $file_name = 'profile_' . time() . '.' . $file_type;

            Storage::disk('public')->delete('/profile/' . $old_user_image);
            $request->image->storeAs('profile', $file_name, 'public');

            DB::table('users')
                ->where('id', $user_id)
                ->update(['image' => $file_name]);

            return redirect(route('profile'))->with('success', 'Image uploaded successfully!');
        }
        return redirect(route('profile'))->with('warning', 'File not found');
    }
    public function uploadBannerPic(Request $request)
    {
        if ($request->hasFile('banner')) {

            $validator = Validator::make($request->all(), [
                'banner' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return redirect(route('profile'))->with('error', $errors);
            }

            $user_id = Auth::user()->id;

            $file_type = $request->banner->extension();
            $file_name = 'banner_' . time() . '.' . $file_type;

            if (Auth::user()->banner != null) {
                Storage::disk('public')->delete('/banner/' . Auth::user()->banner);
            }

            $request->banner->storeAs('banner', $file_name, 'public');

            DB::table('users')
                ->where('id', $user_id)
                ->update(['banner' => $file_name]);

            return redirect(route('profile'))->with('success', 'Image uploaded successfully!');
        }
        return redirect(route('profile'))->with('warning', 'File not found');
    }
    public function changeScoreFormat(Request $request)
    {
        if ($request->score_format == 'null') {
            return redirect()->back()->with('warning', 'score method not changed');
        }

        $validator = Validator::make($request->all(), [
            'score_format' => 'required|in:POINT_100,POINT_10_DECIMAL,POINT_10,POINT_5'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with('error', '¡Ooops!');
        }


        if (Auth::check()) {
            $user = Auth::user();
            $user = User::find($user->id);
            $user->score_format = $request->score_format;
            $user->update();

            return redirect()->back()->with('success', 'score method changed successfully');
        } else {
            return redirect(route('login'));
        }
    }

    public function welcome()
    {
        return view('welcome');
    }
}
