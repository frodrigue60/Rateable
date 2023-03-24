<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Artist;
use App\Models\Song;
use Conner\Tagging\Model\Tag;
use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

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
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $post->thumbnail = $file_name;

                $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                //$request->file->storeAs('thumbnails', $file_name, 'public');
            } else {
                if ($request->thumbnail_src == null) {
                    return Redirect::back()->with('error', "Post not created, images not founds");
                }

                $image_file_data = file_get_contents($request->thumbnail_src);
                //$ext = pathinfo($request->thumbnail_src, PATHINFO_EXTENSION);
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $encoded = Image::make($image_file_data)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                //Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
                $post->thumbnail = $file_name;
                $post->thumbnail_src = $request->thumbnail_src;
            }
            if ($request->theme_num != null) {
                $post->suffix = $request->type . $request->theme_num;
            } else {
                $post->theme_num = null;
            }

            if ($request->artist_id != true) {
                $post->artist_id = null;
            } else {
                $post->artist_id = $request->artist_id;
            }

            if ($request->song_romaji != null || $request->song_en != null || $request->song_en != null) {
                $song = new Song;
                $song->song_romaji = $request->song_romaji;
                $song->song_jp = $request->song_jp;
                $song->song_en = $request->song_en;
                if ($song->save()) {
                    $post->song_id = $song->id;
                } else {
                    $post->song_id = null;
                }
            } else {
                $post->song_id = null;
            }

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
        if (Auth::check() && Auth::user()->type == 'admin' || Auth::user()->type == 'editor') {

            $score_format = Auth::user()->score_format;

            $post = Post::findOrFail($id);
            $artist = $post->artist;
            $tags = $post->tagged;
            //dd($post);
            return view('admin.posts.show', compact('post', 'tags', 'score_format', 'artist'));
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

            if ($request->theme_num != null) {
                $post->suffix = $request->type . $request->theme_num;
            } else {
                $post->theme_num = null;
            }
            if ($request->artist_id != null) {
                $post->artist_id = $request->artist_id;
            } else {
                $post->artist_id = null;
            }

            if ($request->hasFile('file')) {
                $validator = Validator::make($request->all(), [
                    'file' => 'mimes:png,jpg,jpeg,webp|max:1024'
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
                if ($request->thumbnail_src == null) {
                    return redirect(route('admin.post.index'))->with('error', 'Post not created, images not founds');
                }
                Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);
                $image_file_data = file_get_contents($request->thumbnail_src);
                //$ext = pathinfo($request->thumbnail_src, PATHINFO_EXTENSION);
                $file_name = Str::slug($request->title) . time() . '.' . 'webp';
                $encoded = Image::make($image_file_data)->resize(200, 293)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                //Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
                $post->thumbnail = $file_name;
                $post->thumbnail_src = $request->thumbnail_src;
            }

            if ($post->song_id != null) {
                if ($request->song_romaji != $post->song->song_romaji || $request->song_en != $post->song->song_en || $request->song_jp != $post->song->song_jp) {
                    $song = Song::find($post->song_id);
                    $song->song_romaji = $request->song_romaji;
                    $song->song_jp = $request->song_jp;
                    $song->song_en = $request->song_en;
                    $song->update();
                }
            } else {
                if ($request->song_romaji != null || $request->song_en != null || $request->song_en != null) {
                    $song = new Song;
                    $song->song_romaji = $request->song_romaji;
                    $song->song_jp = $request->song_jp;
                    $song->song_en = $request->song_en;
                    if ($song->save()) {
                        $post->song_id = $song->id;
                    } else {
                        $post->song_id = null;
                    }
                }
            }


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
        if ($post->song_id != null) {
            $song = Song::find($post->song_id);
            $song->delete();
        }
        $post->delete();


        return Redirect::route('admin.post.index')->with('success', 'Post Deleted successfully!');
    }

    //seach posts in admin pannel
    public function search(Request $request)
    {
        $posts = Post::query()
            ->where('title', 'LIKE', "%{$request->input('q')}%")
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
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
    public function forceUpdate()
    {
        if (Auth::check()) {
            return true;
        } else {
            return false;
        }
    }
}
