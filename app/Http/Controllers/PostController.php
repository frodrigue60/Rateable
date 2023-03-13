<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Song;
use App\Models\User;
use Conner\Tagging\Model\Tag;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use stdClass;
use Intervention\Image\ImageManagerStatic as Image;


use function PHPSTORM_META\type;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->paginate(10);
        //dd($posts);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];

        $postStatus = [
            ['name' => 'Stagged', 'value' => 'stagged'],
            ['name' => 'Published', 'value' => 'published']
        ];

        $tags = Tag::all();
        $artists = Artist::all();
        return view('admin.posts.create', compact('tags', 'types', 'artists', 'postStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::User()->type;
        if ($user == 'admin' || $user == 'editor' || $user == 'creator') {

            $post = new Post;
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);

            $post->type = $request->type;
            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;

            switch (Auth::user()->type) {
                case 'creator':
                    $post->status = 'stagged';
                    break;
                case 'admin' || 'editor':
                    if ($request->postStatus == null) {
                        $post->status = 'stagged';
                    } else {
                        $post->status = $request->postStatus;
                    }
                    break;
                default:
                    $post->status = 'stagged';
                    break;
            }

            if ($request->hasFile('file')) {
                $validator = Validator::make($request->all(), [
                    'file' => 'mimes:png,jpg,jpeg,webp|max:2048'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    return Redirect::back()->with('error', $errors);
                }
                //$file_extension = $request->file->extension();
                $file_name = Str::slug($request->title) . '_' . time() . '.' . 'webp';
                $post->thumbnail = $file_name;

                $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                //$request->file->storeAs('thumbnails', $file_name, 'public');
            } else {
                if ($request->imageSrc == null) {
                    return Redirect::back()->with('error', "Post not created, images not founds");
                }

                $image_file_data = file_get_contents($request->imageSrc);
                //$ext = pathinfo($request->imageSrc, PATHINFO_EXTENSION);
                $file_name = Str::slug($request->title) . '_' . time() . '.' . 'webp';
                $encoded = Image::make($image_file_data)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                //Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
                $post->thumbnail = $file_name;
                $post->imageSrc = $request->imageSrc;
            }
            if ($request->themeNum != true) {
                $post->themeNum = null;
            } else {
                $post->themeNum = $request->themeNum;
                $post->suffix = $request->type . $request->themeNum;
            }

            if ($request->artist_id != true) {
                $post->artist_id = null;
            } else {
                $post->artist_id = $request->artist_id;
            }
            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();

            $post->song_id = $song->id;

            if ($post->save()) {
                $tags = $request->tags;
                $post->tag($tags);

                $success = 'Post created successfully';
                return redirect(route('admin.post.index'))->with('success', $success);
            } else {
                $error = 'Somethis was wrong!';
                return redirect(route('admin.post.index'))->with('error', $error);
            }
        } else {
            $error = 'User is not authorized!';
            return redirect(route('admin.post.index'))->with('error', $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::check() && Auth::user()->type == 'admin') {

            $score_format = Auth::user()->score_format;

            $post = Post::findOrFail($id);
            $artist = $post->artist;
            $tags = $post->tagged;
            //dd($post);
            return view('show', compact('post', 'tags', 'score_format', 'artist'));
        }
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            $post = Post::findOrFail($id);
            $tags = $post->tagged;
            $artist = $post->artist;
            return view('show', compact('post', 'tags', 'score_format', 'artist'));
        } else {
            $post = Post::findOrFail($id);
            $tags = $post->tagged;
            $artist = $post->artist;

            return view('show', compact('post', 'tags', 'artist'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];

        $postStatus = [
            ['name' => 'Stagged', 'value' => 'stagged'],
            ['name' => 'Published', 'value' => 'published']
        ];

        $post = Post::find($id);
        $song = Song::find($post->song_id);
        $tags = Tag::all();
        $artists = Artist::all();

        return view('admin.posts.edit', compact('post', 'tags', 'types', 'artists', 'song', 'postStatus'));
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
        $user = Auth::User()->type;
        if ($user == 'admin' || $user == 'editor' || $user == 'creator') {
            $post = Post::find($id);
            $old_thumbnail = $post->thumbnail;
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
            $post->type = $request->type;
            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;

            switch (Auth::user()->type) {
                case 'creator':
                    $post->status = 'stagged';
                    break;
                case 'admin' || 'editor':
                    if ($request->postStatus == null) {
                        $post->status = 'stagged';
                    } else {
                        $post->status = $request->postStatus;
                    }
                    break;
                default:
                    $post->status = 'stagged';
                    break;
            }

            if ($request->themeNum != true) {
                $post->themeNum = null;
            } else {
                $post->themeNum = $request->themeNum;
                $post->suffix = $request->type . $request->themeNum;
            }
            if ($request->artist_id != true) {
                $post->artist_id = null;
            } else {
                $post->artist_id = $request->artist_id;
            }

            if ($request->hasFile('file')) {
                $validator = Validator::make($request->all(), [
                    'file' => 'mimes:png,jpg,jpeg,webp|max:2048'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    return Redirect::back()->with('error', $errors);
                }

                //$file_extension = $request->file->extension();
                //$file_mime_type = $request->file->getClientMimeType();

                Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);

                $file_name = Str::slug($request->title) . '_' . time() . '.' . 'webp';
                $post->thumbnail = $file_name;
                //$request->file->storeAs('thumbnails', $file_name, 'public');
                $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
            } else {
                if ($request->imageSrc == null) {
                    return redirect(route('admin.post.index'))->with('error', 'Post not created, images not founds');
                }
                Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);
                $image_file_data = file_get_contents($request->imageSrc);
                //$ext = pathinfo($request->imageSrc, PATHINFO_EXTENSION);
                $file_name = Str::slug($request->title) . time() . '.' . 'webp';
                $encoded = Image::make($image_file_data)->resize(200, 293)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                //Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
                $post->thumbnail = $file_name;
                $post->imageSrc = $request->imageSrc;
            }
            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();

            $post->song_id = $song->id;
            if ($post->update()) {
                $tags = $request->tags;
                $post->retag($tags);
                return redirect(route('admin.post.index'))->with('success', 'Post Updated Successfully');
            } else {
                return redirect(route('admin.post.index'))->with('error', 'Something has wrong');
            }
        } else {
            $error = 'User is not authorized!';
            return redirect(route('admin.post.index'))->with('error', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        $file = $post->thumbnail;

        Storage::disk('public')->delete('/thumbnails/' . $file);
        $post->delete();
        $song = Song::find($post->song->id);
        $song->delete();

        return Redirect::back()->with('success', 'Post Deleted successfully!');
    }

    //return index view with all openings

    public function home()
    {
        $recently = Post::all()->sortByDesc('created_at')/* ->take(10) */;
        $popular = Post::all()->sortByDesc('likeCount')/* ->take(10) */;
        $viewed = Post::all()->sortByDesc('viewCount')/* ->take(10) */;

        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }

        $allOpenings = Post::where('type', 'op')
            ->get();
        $allEndings = Post::where('type', 'ed')
            ->get();
        $openings = $allOpenings->sortByDesc('averageRating')->take(10);
        $endings = $allEndings->sortByDesc('averageRating')->take(10);

        return view('index', compact('openings', 'endings', 'recently', 'popular', 'viewed', 'score_format'));
    }

    public function openings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $currentSeason = DB::table('current_season')->first();

        if ($currentSeason == null) {

            $posts = Post::where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('seasonal', compact('posts', 'tags', 'score_format'));
        } else {

            $posts = Post::withAnyTag($currentSeason->name)
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('seasonal', compact('posts', 'tags', 'score_format', 'currentSeason'));
        }
    }
    public function endings()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $currentSeason = DB::table('current_season')->first();

        if ($currentSeason == null) {

            $posts = Post::where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('seasonal', compact('posts', 'tags', 'score_format'));
        } else {

            $posts = Post::withAnyTag($currentSeason->name)
                ->where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            $tags = DB::table('tagging_tags')
                ->orderBy('name', 'desc')
                ->take(5)
                ->get();

            return view('seasonal', compact('posts', 'tags', 'score_format', 'currentSeason'));
        }
    }

    public function ratePost(Request $request, $id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $score = $request->score;
            $score_format = $request->score_format;

            if (blank($score)) {
                return redirect()->back()->with('warning', 'Score can not be null');
            }
            switch ($score_format) {
                case 'POINT_100':
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 100)) {
                        $post->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;

                case 'POINT_10_DECIMAL':
                    settype($score, "float");
                    if (($score >= 1) && ($score <= 10)) {
                        $int = intval($score * 10);
                        $post->rateOnce($int);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (can use decimals)');
                    }
                    break;
                case 'POINT_10':
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 10)) {
                        $int = intval($score * 10);
                        $post->rateOnce($int);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (only integer numbers)');
                    }
                    break;
                case 'POINT_5':
                    settype($score, "integer");

                    if (($score >= 1) && ($score <= 100)) {
                        if ($score <= 20) {
                            $score = 20;
                        }
                        if (($score > 20) && ($score <= 40)) {
                            $score = 40;
                        }
                        if (($score > 40) && ($score <= 60)) {
                            $score = 60;
                        }
                        if (($score > 60) && ($score <= 80)) {
                            $score = 80;
                        }
                        if ($score > 80) {
                            $score = 100;
                        }
                        $post->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;


                default:
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 100)) {
                        $post->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;
            }
        }
        return redirect()->route('login');
    }

    public function favorites(Request $request)
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            $user = Auth::user();
        } else {
            return redirect()->route('login');
        }

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
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
            case 'viewCount':
                $posts = $posts->sortByDesc('viewCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));

            case 'likeCount':
                $posts = $posts->sortByDesc('likeCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
            case 'recent':
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;

            default:
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format', 'user', 'filters'));
                break;
        }
    }

    public function likePost($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Post::find($id)->like($userId);

            return Redirect::back()->with('success', 'Post Like successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function unlikePost($id)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Post::find($id)->unlike($userId);

            return Redirect::back()->with('success', 'Post Like undo successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function approve($id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $post->status = 'published';
            $post->update();
            return Redirect::back()->with('success', 'Post ' . $post->id . ' Approved successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }
    public function unapprove($id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $post->status = 'stagged';
            $post->update();
            return Redirect::back()->with('warning', 'Post ' . $post->id . ' Unapproved successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    //public seasrch posts
    public function filter(Request $request)
    {
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

        if ($tag != null) {
            if ($type != null) {
                if ($char != null) {
                    $posts = Post::withAnyTag($tag)
                        ->where('type', $type)
                        ->where('title', 'LIKE', "{$char}%")
                        ->get();
                } else {
                    $posts = Post::withAnyTag($tag)
                        ->where('type', $type)
                        ->get();
                }
            } else {
                if ($char != null) {
                    $posts = Post::withAnyTag($tag)
                        ->where('title', 'LIKE', "{$char}%")
                        ->get();
                } else {
                    $posts = Post::withAnyTag($tag)->get();
                }
            }
        } else {
            if ($type != null) {
                if ($char != null) {
                    $posts = Post::where('type', $type)
                        ->where('title', 'LIKE', "{$char}%")
                        ->get();
                } else {
                    $posts = Post::where('type', $type)
                        ->get();
                }
            } else {
                if ($char != null) {
                    $posts = Post::where('title', 'LIKE', "{$char}%")
                        ->get();
                } else {
                    $posts = Post::all();
                }
            }
        }

        //SWITCH ORDER THE POSTS
        switch ($sort) {
            case 'title':
                $posts = $posts->sortBy('title');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
                break;
            case 'averageRating':
                $posts = $posts->sortByDesc('averageRating');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
            case 'viewCount':
                $posts = $posts->sortByDesc('viewCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));

            case 'likeCount':
                $posts = $posts->sortByDesc('likeCount');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
                break;
            case 'recent':
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
                break;

            default:
                $posts = $posts->sortByDesc('created_at');
                $posts = $this->paginate($posts)->withQueryString();
                return view('filter', compact('posts', 'tags', 'requested', 'sortMethods', 'types', 'characters', 'score_format'));
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

    //seach posts in admin pannel
    public function searchPost(Request $request)
    {
        if (Auth::check() && Auth::user()->type == 'admin') {
            $posts = Post::query()
                ->where('title', 'LIKE', "%{$request->input('search')}%")
                ->paginate(10);

            return view('admin.posts.index', compact('posts'));
        } else {
            return redirect()->route('/')->with('error', 'Only admins');
        }
    }

    public function seasonalranking()
    {
        $currentSeason = DB::table('current_season')->first();
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        if ($currentSeason == null) {
            $op_count = Post::where('type', 'op')->count();
            $ed_count = Post::where('type', 'ed')->count();

            $openings = Post::where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();

            $endings = Post::where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();

            return view('ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'score_format'));
        } else {
            //search the current season and the posts
            $currentSeason = DB::table('current_season')->first();

            $openings = Post::withAnyTag($currentSeason->name)
                ->where('type', 'op')
                ->orderBy('title', 'asc')
                ->get();
            $op_count = $openings->count();

            $endings = Post::withAnyTag($currentSeason->name)
                ->where('type', 'ed')
                ->orderBy('title', 'asc')
                ->get();
            $ed_count = $endings->count();


            //dd($currentSeason, $op_count, $ed_count, $openings, $endings);

            return view('ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'currentSeason', 'score_format'));
        }
    }
    public function globalrank()
    {
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        $getOpenings = Post::where('type', 'op')
            ->orderBy('title', 'asc')
            ->get();
        $op_count = $getOpenings->count();

        $openings = $getOpenings->sortByDesc('averageRating')->take(100);

        $getEndings = Post::where('type', 'ed')
            ->orderBy('title', 'asc')
            ->get();
        $ed_count = $getEndings->count();

        $endings = $getEndings->sortByDesc('averageRating')->take(100);

        return view('ranking', compact('openings', 'endings', 'op_count', 'ed_count', 'score_format'));
    }

    public function showBySlug($id, $slug)
    {
        /* if (Auth::check() && Auth::user()->type == 'admin') {
            $score_format = Auth::user()->score_format;

            $post = Post::where('id', '=', $id)->first();
            
            $artist = $post->artist;
            $tags = $post->tagged;
            //dd($post);
            return view('show', compact('post', 'tags', 'score_format', 'artist'));
        } */
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
            $post = Post::findOrFail($id);
            $tags = $post->tagged;
            $artist = $post->artist;
            $this->count_views($post);
            return view('show', compact('post', 'tags', 'score_format', 'artist'));
        } else {
            $post = Post::findOrFail($id);
            $tags = $post->tagged;
            $artist = $post->artist;
            $this->count_views($post);

            return view('show', compact('post', 'tags', 'artist'));
        }
    }
    public function count_views($post)
    {
        if (!Session::has('page_visited_' . $post->id)) {
            DB::table('posts')
                ->where('id', $post->id)
                ->increment('viewCount');
            Session::put('page_visited_' . $post->id, true);
        }
    }
    public function forceUpdate()
    {
        dd(true);
    }
}
