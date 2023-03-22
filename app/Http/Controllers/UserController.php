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
use App\Models\Post;
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
            ['name' => 'Views', 'value' => 'viewCount'],
            ['name' => 'Popular', 'value' => 'likeCount']
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
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
            case 'viewCount':
                $posts = $posts->sortByDesc('viewCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));

            case 'likeCount':
                $posts = $posts->sortByDesc('likeCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
            case 'recent':
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;

            default:
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('public.posts.filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
        }
    }

    public function paginate($posts, $perPage = 18, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $posts instanceof Collection ? $posts : Collection::make($posts);
        $posts = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $posts;
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
        }
        //return Redirect::back()->with('error', '¡Ooops!');
    }

    public function welcome()
    {
        return view('welcome');
    }
}
