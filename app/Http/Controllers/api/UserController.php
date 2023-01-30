<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Conner\Tagging\Model\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;
use stdClass;

class UserController extends Controller
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
    public function userList (Request $request, $userId){
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

        $filters = [
            ['name' => 'All', 'value' => 'all'],
            ['name' => 'Only Rated', 'value' => 'rated']
        ];

        $types = [
            ['name' => 'Opening','value'=>'OP'],
            ['name' => 'Ending','value'=>'ED']
        ];

        $sortMethods = [
            ['name' => 'Recent','value'=>'recent'],
            ['name' => 'Title','value'=>'title'],
            ['name' => 'Score','value'=>'averageRating'],
            ['name' => 'Views','value'=>'viewCount'],
            ['name' => 'Popular','value'=>'likeCount']
        ];

        $characters = range('A', 'Z');

        switch ($filterBy) {
            case 'all':
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->whereLikedBy($user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::where('type', $type)
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            $posts = Post::whereLikedBy($user->id)

                                ->with('likeCounter')->get();
                        }
                    }
                }
                break;
            case 'rated':
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('type', $type)
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::where('type', $type)
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            $posts = Post::whereLikedBy($user->id)
                                ->join('ratings', 'posts.id', '=', 'ratings.rateable_id')
                                ->where('ratings.user_id', '=', $user->id)
                                ->with('likeCounter')->get();
                        }
                    }
                }
                break;
            default:
                if ($tag != null) {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->where('type', $type)
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::withAnyTag($tag)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::withAnyTag($tag)
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        }
                    }
                } else {
                    if ($type != null) {
                        if ($char != null) {
                            $posts = Post::where('type', $type)
                                ->where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            $posts = Post::where('type', $type)
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        }
                    } else {
                        if ($char != null) {
                            $posts = Post::where('title', 'LIKE', "{$char}%")
                                ->whereLikedBy($user->id)

                                ->with('likeCounter')
                                ->get();
                        } else {
                            //DEFAULT POSTS
                            $posts = Post::whereLikedBy($user->id)

                                ->with('likeCounter')->get();
                        }
                    }
                }
                break;
        }

        switch ($sort) {
            case 'title':
                $posts = $posts->sortBy('title');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user','filters'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user','filters'));
            case 'viewCount':
                $posts = $posts->sortByDesc('viewCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user','filters'));

            case 'likeCount':
                $posts = $posts->sortByDesc('likeCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user','filters'));
                break;
            case 'recent':
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user','filters'));
                break;

            default:
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user','filters'));
                break;
        }
    }
    public function paginate($posts, $perPage = 20, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $posts instanceof Collection ? $posts : Collection::make($posts);
        $posts = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $posts;
    }
}
