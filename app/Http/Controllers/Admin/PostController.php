<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Artist;
use App\Models\Song;
use Conner\Tagging\Model\Tag;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->paginate(20);
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
            $post->description = $request->description;

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
                    $request->flash();
                    return Redirect::back()
                        ->with('error', $errors);
                }
                //$file_extension = $request->file->extension();
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $post->thumbnail = $file_name;

                $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                //$request->file->storeAs('thumbnails', $file_name, 'public');
            } else {
                if ($request->thumbnail_src == null) {
                    //dd($request->all());
                    $request->flash();
                    return Redirect::back()
                        ->with('error', "Post not created, images not founds");
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
            if ($request->hasFile('banner')) {
                $validator = Validator::make($request->all(), [
                    'banner' => 'mimes:png,jpg,jpeg,webp|max:2048'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    $request->flash();
                    return Redirect::back()
                        ->with('error', $errors);
                }
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $post->banner = $file_name;

                $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/anime_banner/' . $file_name, $encoded);
            } else {
                if ($request->banner_src != null) {
                    $banner_file_data = file_get_contents($request->banner_src);
                    $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                    $encoded = Image::make($banner_file_data)->encode('webp', 100); //->resize(150, 212)
                    Storage::disk('public')->put('/anime_banner/' . $file_name, $encoded);
                    $post->banner = $file_name;
                    $post->banner_src = $request->banner_src;
                } else {
                    $post->banner_src = null;
                }
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

            $ops = $post->songs->filter(function ($song) {
                return $song->type === 'OP';
            });
            $eds = $post->songs->filter(function ($song) {
                return $song->type === 'ED';
            });

            $artist = $post->artist;
            $tags = $post->tagged;
            //dd($post);
            return view('admin.posts.show', compact('post', 'tags', 'score_format', 'artist', 'ops', 'eds'));
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
            $old_banner = $post->banner;
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
            $post->description = $request->description;


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
                    'file' => 'mimes:png,jpg,jpeg,webp|max:1024'
                ]);

                if ($validator->fails()) {
                    $messageBag = $validator->getMessageBag();
                    return Redirect::back()->with('error', $messageBag)->with('messageBag', $messageBag);
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

            if ($request->hasFile('banner')) {
                $validator = Validator::make($request->all(), [
                    'banner' => 'mimes:png,jpg,jpeg,webp|max:2048'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    $request->flash();
                    return Redirect::back()
                        ->with('error', $errors);
                }
                Storage::disk('public')->delete('/anime_banner/' . $old_banner);
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $post->banner = $file_name;

                $encoded = Image::make($request->file)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/anime_banner/' . $file_name, $encoded);
            } else {
                if ($request->banner_src != null) {
                    Storage::disk('public')->delete('/anime_banner/' . $old_banner);
                    $banner_file_data = file_get_contents($request->banner_src);
                    $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                    $encoded = Image::make($banner_file_data)->encode('webp', 100); //->resize(150, 212)
                    Storage::disk('public')->put('/anime_banner/' . $file_name, $encoded);
                    $post->banner = $file_name;
                    $post->banner_src = $request->banner_src;
                } else {
                    $post->banner_src = null;
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
        $banner = $post->banner;

        Storage::disk('public')->delete('/thumbnails/' . $file);
        Storage::disk('public')->delete('/anime_banner/' . $banner);
        if ($post->songs != null) {
            $songs = $post->songs;
            foreach ($songs as $song) {
                Song::find($song->id)->delete();
            }
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

    public function searchAnimes(Request $request)
    {
        $q = $request->q;
        $client = new \GuzzleHttp\Client();

        $query = '
            query ($search: String) {
                Page {
                    media (search: $search, type: ANIME, format: TV) {
                        id
                        title {
                            romaji
                            english
                            native
                        }
                        description
                        season
                        seasonYear
                        averageScore
                        coverImage {
                            extraLarge
                        }
                        bannerImage
                    }
                }
            }
        ';

        $variables = [
            'search' => $q,
        ];

        $response = $client->post('https://graphql.anilist.co', [
            'json' => [
                'query' => $query,
                'variables' => $variables,
            ]
        ]);

        $body = $response->getBody()->__toString();

        $json = json_decode($body);
        $data = $json->data->Page->media;
        $posts = [];
        foreach ($data as $item) {
            array_push($posts, $item);
        }
        //dd($posts);
        return view('admin.posts.select', compact('posts'));
    }
    public function getById(Request $request)
    {
        //dd($request->all());
        // Here we define our query as a multi-line string
        $query = '
        query ($id: Int) { # Define which variables will be used in the query (id)
            Media (id: $id, type: ANIME, format: TV) { # Insert our variables into the query arguments (id) (type: ANIME is hard-coded in the query)
                id
                title {
                romaji
                english
                native
                }
                description
                season
                seasonYear
                averageScore
                coverImage {
                    large
                    extraLarge
                }
                bannerImage
            }
        }
        ';

        $variables = [
            "id" => $request->id
        ];
        //dd($variables);

        $client = new \GuzzleHttp\Client;
        $response = $client->post('https://graphql.anilist.co', [
            'json' => [
                'query' => $query,
                'variables' => $variables,
            ]
        ]);
        $body = $response->getBody()->__toString();
        $json = json_decode($body);
        
        $data[] = $json->data->Media;
        $this->generateMassive($data);
        $success = 'Posts created successfully';
        return redirect(route('admin.post.index'))->with('success', $success);
    }
    public function getSeasonalAnimes(Request $request)
    {

        $year = $request->year;
        $season = $request->season;

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min_digits:4|max_digits:4',
        ]);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();
            return redirect(route('admin.post.index'))->with('error', $messageBag);
        }

        $client = new \GuzzleHttp\Client();

        if ($season != null) {
            $query = '
            query ($year: Int, $season: MediaSeason, $page: Int, $perPage: Int) {
                Page (page: $page, perPage: $perPage) {
                    pageInfo {
                        total
                        perPage
                        currentPage
                        lastPage
                        hasNextPage
                    }
                    media (seasonYear: $year, season: $season, type: ANIME, format: TV) {
                        id
                        title {
                            romaji
                            english
                            native
                        }
                        description
                        season
                        seasonYear
                        averageScore
                        coverImage {
                            extraLarge
                        }
                        bannerImage
                    }
                }
            }
        ';
        } else {
            $query = '
            query ($year: Int, $page: Int, $perPage: Int) {
                Page (page: $page, perPage: $perPage) {
                    pageInfo {
                        total
                        perPage
                        currentPage
                        lastPage
                        hasNextPage
                    }
                    media (seasonYear: $year, type: ANIME, format: TV) {
                        id
                        title {
                            romaji
                            english
                            native
                        }
                        description
                        season
                        seasonYear
                        averageScore
                        coverImage {
                            extraLarge
                        }
                        bannerImage
                    }
                }
            }
        ';
        }

        $variables = [
            'year' => $year,
            'season' => $season,
            'page' => 1,
            'perPage' => 50,
        ];

        $hasNextPage = true;

        $data = [];
        while ($hasNextPage) {
            $response = $client->post('https://graphql.anilist.co', [
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ]
            ]);

            $body = $response->getBody()->__toString();
            $json = json_decode($body);

            $collection = $json->data->Page->media;
            $pageInfo = $json->data->Page->pageInfo;
            $hasNextPage = $pageInfo->hasNextPage;
            $variables['page']++;
            foreach ($collection as $item) {
                array_push($data, $item);
            }
        }

        //dd($data);
        $this->generateMassive($data);
        $success = count($data) . ' Posts created successfully ';
        return redirect(route('admin.post.index'))->with('success', $success);
    }

    public function generateMassive($data)
    {
        $tag = $data[0]->season . ' ' . $data[0]->seasonYear;
        $tag_exist = DB::table('tagging_tags')->where('name', $tag)->first();
        if (!$tag_exist) {
            DB::table('tagging_tags')->insert([
                'name' => $tag,
                'slug' => Str::slug($tag)
            ]);
        }
        //dd($tag,$tag_exist);
        foreach ($data as $item) {
            $post_exist = Post::where('title', $item->title->romaji)->first();
            
            if ($post_exist) {
                continue;
            }
            $post = new Post;
            $post->title = $item->title->romaji;
            $post->slug = Str::slug($post->title);
            $post->description = $item->description;
            $post->anilist_id = $item->id;
            /* $post->status = 'published'; */
            if ($item->coverImage->extraLarge != null) {
                $image_file_data = file_get_contents($item->coverImage->extraLarge);
                $file_name = Str::slug($post->slug) . '-' . time() . '.' . 'webp';
                $encoded = Image::make($image_file_data)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/thumbnails/' . $file_name, $encoded);
                $post->thumbnail = $file_name;
                $post->thumbnail_src = $item->coverImage->extraLarge;
            } else {
                $post->thumbnail = null;
                $post->thumbnail_src = null;
            }

            if ($item->bannerImage != null) {
                $banner_file_data = file_get_contents($item->bannerImage);
                $file_name = Str::slug($post->slug) . '-' . time() . '.' . 'webp';
                $encoded = Image::make($banner_file_data)->encode('webp', 100); //->resize(150, 212)
                Storage::disk('public')->put('/anime_banner/' . $file_name, $encoded);
                $post->banner = $file_name;
                $post->banner_src = $item->bannerImage;
            } else {
                $post->banner = null;
                $post->banner_src = null;
            }

            if ($post->save()) {
                $post->tag($tag);
            }
        }
    }

    public function forceUpdate()
    {
        return redirect(route('admin.post.index'))->with('warning', 'force update');
    }
    public function wipeAllPosts()
    {
        $posts = Post::all();
        foreach ($posts as $post) {
            $post->delete();
        }
        $success = 'All posts deleted';
        return redirect(route('admin.post.index'))->with('success', $success);
    }
}
