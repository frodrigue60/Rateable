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

        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $tags = Tag::all();
        $tag = $request->tag;
        $type = $request->type;
        $sort = $request->sort;
        $char = $request->char;

        $requested = new stdClass;
        $requested->type = $type;
        $requested->tag = $tag;
        $requested->sort = $sort;
        $requested->char = $char;

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

        /* $user = User::find($id);
        $posts = Post::whereLikedBy($user->id) // find only articles where user liked them
        ->with('likeCounter') // highly suggested to allow eager load
        ->get(); */

        if ($tag != null) {
            if ($type != null) {
                if ($char != null) {
                    $posts = Post::withAnyTag($tag)
                    ->where('type', $type)
                    ->where('title', 'LIKE', "{$char}%")
                    ->whereLikedBy($userId) // find only articles where user liked them
                    ->with('likeCounter')
                    ->get();
                }
                else{
                    $posts = Post::withAnyTag($tag)
                    ->where('type', $type)
                    ->whereLikedBy($user->id) // find only articles where user liked them
                    ->with('likeCounter')
                    ->get();
                }
            } else {
                if ($char != null) {
                    $posts = Post::withAnyTag($tag)
                    ->where('title', 'LIKE', "{$char}%")
                    ->whereLikedBy($user->id) // find only articles where user liked them
                    ->with('likeCounter')
                    ->get();
                } else {
                    $posts = Post::withAnyTag($tag)
                    ->whereLikedBy($user->id) // find only articles where user liked them
                    ->with('likeCounter')
                    ->get();
                }
            }
        } else {
            if ($type != null) {
                if ($char != null) {
                    $posts = Post::where('type', $type)
                    ->where('title', 'LIKE', "{$char}%")
                    ->whereLikedBy($user->id) // find only articles where user liked them
                    ->with('likeCounter')
                    ->get();
                } else {
                    $posts = Post::where('type', $type)
                    ->whereLikedBy($user->id) // find only articles where user liked them
                    ->with('likeCounter')
                    ->get();
                }
            } else {
                if ($char != null) {
                    $posts = Post::where('title', 'LIKE', "{$char}%")
                    ->whereLikedBy($user->id) // find only articles where user liked them
                    ->with('likeCounter')
                    ->get();
                } else {
                    $posts = Post::whereLikedBy($user->id) // find only articles where user liked them
                    ->with('likeCounter')->get();
                }
            }
        }

        switch ($sort) {
            case 'title':
                $posts = $posts->sortBy('title');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user'));
            case 'viewCount':
                $posts = $posts->sortByDesc('viewCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user'));

            case 'likeCount':
                $posts = $posts->sortByDesc('likeCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user'));
                break;
            case 'recent':
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user'));
                break;

            default:
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested','sortMethods','types','characters','score_format','user'));
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
