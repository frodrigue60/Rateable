<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use App\Models\Post;
use Conner\Tagging\Model\Tag;
use App\Models\Artist;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return $posts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $validator = Validator::make($request->all(), [
                'file' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return $errors;
            }


            $post = new Post;
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
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

            $post->type = $request->type;
            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;
            //$file_extension = $request->file->extension();
            /* $file_mime_type = $request->file->getClientMimeType();  NOT USED*/
            $file_name = 'thumbnail_' . time() . '.' . 'webp';
            $post->thumbnail = $file_name;

            $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
            Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
            //$request->file->storeAs('thumbnails', $file_name, 'public');
            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();

            $post->song_id = $song->id;

            $post->save();

            $tags = $request->tags;
            $post->tag($tags);

            if ($post->save()) {
                return "Post created Successfully, has file";
            } else {
                return "Somethis was wrong!";
            }
        } else {
            $post = new Post;
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
            $post->type = $request->type;

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

            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;

            if ($request->imageSrc == null) {
                return "Post not created, images not founds";
            }

            $image_file_data = file_get_contents($request->imageSrc);
            //$ext = pathinfo($request->imageSrc, PATHINFO_EXTENSION);
            $file_name = 'thumbnail_' . time() . '.' . 'webp';
            $encoded = Image::make($image_file_data)->encode('webp', 100); //->resize(150, 212)
            Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
            //Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
            $post->thumbnail = $file_name;
            $post->imageSrc = $request->imageSrc;

            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();

            $post->song_id = $song->id;

            $post->save();
            $tags = $request->tags;
            $post->tag($tags);

            if ($post->save()) {
                return "Success";
            } else {
                return "Somethis was wrong!";
            }
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
        $post = Post::find($id);
        return $post;
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
        if ($request->hasFile('file')) {
            $validator = Validator::make($request->all(), [
                'file' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return $errors;
            }

            $post = Post::find($id);
            $old_thumbnail = $post->thumbnail;
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
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

            $post->type = $request->type;

            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;

            //$file_extension = $request->file->extension();
            //$file_mime_type = $request->file->getClientMimeType();


            Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);

            $file_name = 'thumbnail_' . time() . '.' . 'webp';
            $post->thumbnail = $file_name;
            //$request->file->storeAs('thumbnails', $file_name, 'public');
            $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
            Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();
            $post->song_id = $song->id;
            $post->update();

            $tags = $request->tags;
            $post->tag($tags);
            return "Post updated Successfully, has file image";
        } else {
            $post = Post::find($id);
            $old_thumbnail = $post->thumbnail;

            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
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

            $post->type = $request->type;

            $post->ytlink = $request->ytlink;
            $post->scndlink = $request->scndlink;
            if ($request->imageSrc == null) {
                return "Post not updated, images not founds";
            }
            Storage::disk('public')->delete('/thumbnails/' . $old_thumbnail);
            $image_file_data = file_get_contents($request->imageSrc);
            //$ext = pathinfo($request->imageSrc, PATHINFO_EXTENSION);
            $file_name = 'thumbnail_' . time() . '.' . 'webp';
            $encoded = Image::make($image_file_data)->encode('webp', 100); //->resize(150, 212)
            Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
            //Storage::disk('public')->put('/thumbnails/' . $file_name, $image_file_data);
            $post->thumbnail = $file_name;
            $post->imageSrc = $request->imageSrc;

            $song = new Song;
            $song->song_romaji = $request->song_romaji;
            $song->song_jp = $request->song_jp;
            $song->song_en = $request->song_en;
            $song->save();

            $post->song_id = $song->id;
            $post->update();
            $tags = $request->tags;
            $post->retag($tags);
            return "Post Updated Successfully, has url image";
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

        if ($post->delete()) {
            return 'Post deleted successfully';
        } else {
            return 'Post was wrong!';
        }
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        //dd($q);
        $posts = Post::where('title', 'LIKE', "%$q%")->orWhere('suffix', 'LIKE', "%$q%")->limit(5)->get(['id', 'title', 'slug', 'type', 'themeNum', 'suffix']);

        $artists = Artist::where('name', 'LIKE', "%$q%")->limit(5)->get(['name', 'name_slug']);

        $tags = Tag::where('name', 'LIKE', "%$q%")->limit(5)->get(['name', 'slug']);

        $users = User::where('name', 'LIKE', "%$q%")->limit(5)->get(['id','name']);

        $data = ["posts" => $posts, "artists" => $artists, "tags" => $tags,"users" => $users];

        return response()->json($data);
    }
}
