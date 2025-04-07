<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Artist;
use App\Http\Controllers\Controller;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use App\Models\Year;
use GuzzleHttp\Client;
use App\Services\Breadcrumb;

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

        $breadcrumb = Breadcrumb::generate([
            [
                'name' => 'Index',
                'url' => route('admin.posts.index'),
            ],
        ]);
        //dd($posts);
        return view('admin.posts.index', compact('posts', 'breadcrumb'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $artists = Artist::all();
        $seasons = Season::all();
        $years = Year::all();

        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];

        $postStatus = [
            ['name' => 'Stagged', 'value' => 'stagged'],
            ['name' => 'Published', 'value' => 'published']
        ];

        $breadcrumb = Breadcrumb::generate([
            [
                'name' => 'Index',
                'url' => route('admin.posts.index'),
            ],
            [
                'name' => 'Create post',
                'url' => '',
            ],
        ]);

        return view('admin.posts.create', compact('years','seasons','types', 'artists', 'postStatus', 'breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        /* return redirect()->back()
                         ->with('error', 'Hubo un error al procesar el formulario.')
                         ->withInput(); */
        $user = Auth::User()->type;
        if ($user == 'admin' || $user == 'editor' || $user == 'creator') {

            $post = new Post;
            $post->title = $request->title;
            $post->slug = Str::slug($request->title);
            $post->description = $request->description;

            switch (Auth::user()->type) {
                case 'creator':
                    $post->status = false;
                    break;
                case 'admin' || 'editor':
                    if ($request->postStatus == null) {
                        $post->status = false;
                    } else {
                        $post->status = $request->postStatus;
                    }
                    break;
                default:
                    $post->status = true;
                    break;
            }

            $this->storePostImages($post, $request);

            if ($post->save()) {
                $post->retag($request->tags);

                $success = 'Post created successfully';
                return redirect(route('admin.posts.index'))->with('success', $success);
            } else {
                $error = 'Somethis was wrong!';
                return redirect(route('admin.posts.index'))->with('error', $error);
            }
        } else {
            $error = 'User is not authorized!';
            return redirect(route('admin.posts.index'))->with('error', $error);
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
        if (Auth::check() && Auth::user()->isStaff()) {

            $post = Post::findOrFail($id);
            $score_format = Auth::user()->score_format;

            $breadcrumb = Breadcrumb::generate([
                [
                    'name' => 'Index',
                    'url' => route('admin.posts.index'),
                ],
                [
                    'name' => $post->title,
                    'url' => route('admin.posts.show', $post->id),
                ],
            ]);

            $ops = $post->songs->filter(function ($song) {
                return $song->type === 'OP';
            });
            $eds = $post->songs->filter(function ($song) {
                return $song->type === 'ED';
            });

            //dd($ops, $eds);
            return view('admin.posts.show', compact('post', 'score_format', 'ops', 'eds', 'breadcrumb'));
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
        $post = Post::find($id);
        $artists = Artist::all();
        $seasons = Season::all();
        $years = Year::all();

        $types = [
            ['name' => 'Opening', 'value' => 'OP'],
            ['name' => 'Ending', 'value' => 'ED']
        ];

        $postStatus = [
            ['name' => 'Stagged', 'value' => 'stagged'],
            ['name' => 'Published', 'value' => 'published']
        ];

        $breadcrumb = Breadcrumb::generate([
            [
                'name' => 'Index',
                'url' => route('admin.posts.index'),
            ],
            [
                'name' => $post->title,
                'url' => '',
            ],
        ]);

        return view('admin.posts.edit', compact('post', 'types', 'artists', 'postStatus', 'breadcrumb'));
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
        //dd($request->all());
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
                    $post->status = false;
                    break;
                case 'admin' || 'editor':
                    if ($request->postStatus == null) {
                        $post->status = false;
                    } else {
                        $post->status = $request->postStatus;
                    }
                    break;
                default:
                    $post->status = false;
                    break;
            }

            $this->storePostImages($post, $request);

            if ($post->update()) {

                Storage::disk('public')->delete($old_thumbnail);
                Storage::disk('public')->delete($old_banner);
                return redirect(route('admin.posts.index'))->with('success', 'Post Updated Successfully');
            } else {
                return redirect(route('admin.posts.index'))->with('error', 'Something has wrong');
            }
        } else {
            $error = 'User is not authorized!';
            return redirect(route('admin.posts.index'))->with('error', $error);
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
            return Redirect::route('admin.posts.index')->with('success', 'Post Deleted successfully!');
        } else {
            return Redirect::route('admin.posts.index')->with('error', 'Post has been not deleted!');
        }
    }

    //seach posts in admin pannel
    public function search(Request $request)
    {
        $posts = Post::query()
            ->where('title', 'LIKE', '%'.$request->input('q').'%')
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }
    public function approve($id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $post->status = true;
            $post->update();
            return Redirect::back()->with('success', 'Post ' . $post->id . ' Approved successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }
    public function unapprove($id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $post->status = false;
            $post->update();
            return Redirect::back()->with('warning', 'Post ' . $post->id . ' Unapproved successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function searchAnimes(Request $request)
    {
        //dd($request->all());
        $q = $request->q;
        $arr_types = $request->types;
        

        $variables = [
            'search' => $q,
            'format_in' => $arr_types,
        ];

        $query = $this->buildGraphQLQuerySearch();

        $client = new Client();
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
    public function getById($anilist_id)
    {
        //dd($id);
        // Here we define our query as a multi-line string
        $query = '
        query ($id: Int) { # Define which variables will be used in the query (id)
            Media (id: $id, type: ANIME) { # Insert our variables into the query arguments (id) (type: ANIME is hard-coded in the query)
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
            "id" => $anilist_id
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
        //dd($data);
        $this->generateMassive($data);
        $success = 'Single post created successfully';
        return redirect(route('admin.posts.index'))->with('success', $success);
    }
    public function getSeasonalAnimes(Request $request)
    {

        //dd($request->all());
        $year = $request->year;
        $season = Str::upper($request->season);
        $arr_types = $request->types;

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min_digits:4|max_digits:4',
        ]);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();
            return redirect(route('admin.posts.index'))->with('error', $messageBag);
        }

        $client = new Client();

        if ($season != null) {
            $query = $this->buildGraphQLQuerySeasonal();
        } else {
            dd('error');
        }

        $variables = [
            'year' => $year,
            'season' => $season,
            'page' => 1,
            'perPage' => 50,
            'format_in' => $arr_types,
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
        return redirect(route('admin.posts.index'))->with('success', $success);
    }

    public function generateMassive($data)
    {
        //dd($data);
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
            $post->status = true;

            $post->season_id = null;
            $post->year_id = null;

            $this->saveAnimeBanner($item, $post);
            $this->saveAnimeThumbnail($item, $post);

            //dd($item);

            if (!empty($item->season)) {
                $season = Season::firstOrCreate([
                    'name' =>  $item->season,
                ]);

                $post->season_id = $season->id;
            }

            if (!empty($item->seasonYear)) {
                $year = Year::firstOrCreate([
                    'name' =>  $item->seasonYear,
                ]);

                $post->year_id = $year->id;
            }

            $post->save();
        }
    }

    public function forceUpdate()
    {
        return redirect(route('admin.posts.index'))->with('warning', 'force update');
    }
    public function wipePosts()
    {
        $posts = Post::all();
        foreach ($posts as $post) {
            $post->delete();
        }

        $thumbnail_files = Storage::disk('public')->files('thumbnails');
        Storage::disk('public')->delete($thumbnail_files);

        $banner_files = Storage::disk('public')->files('anime_banner');
        Storage::disk('public')->delete($banner_files);

        $success = 'All posts deleted';
        return redirect(route('admin.posts.index'))->with('success', $success);
    }
    function buildGraphQLQuerySearch()
    {
        $query = '
            query ($search: String, $format_in: [MediaFormat]) {
                Page {
                    media (search: $search, type: ANIME, format_in: $format_in) {
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
                        studios {
                            nodes {
                                name
                            }
                        }
                        coverImage {
                            extraLarge
                        }
                        bannerImage
                    }
                }
            }
        ';
        return $query;
    }
    function buildGraphQLQuerySeasonal()
    {
        $query = '
            query ($year: Int, $season: MediaSeason, $page: Int, $perPage: Int, $format_in: [MediaFormat]) {
                Page (page: $page, perPage: $perPage) {
                    pageInfo {
                        total
                        perPage
                        currentPage
                        lastPage
                        hasNextPage
                    }
                    media (seasonYear: $year, season: $season, type: ANIME,format_in: $format_in, isAdult:false) {
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
                        format
                        genres
                        studios {
                            nodes {
                                name
                            }
                        }
                        coverImage {
                            extraLarge
                        }
                        bannerImage
                        trailer{
                            id
                            site
                            thumbnail
                        }
                    }
                }
            }';
        return $query;
    }
    function buildGraphQLQueryId()
    {
        $query = '
        query ($id: Int) { # Define which variables will be used in the query (id)
            Media (id: $id, type: ANIME) { # Insert our variables into the query arguments (id) (type: ANIME is hard-coded in the query)
                id
                title {
                romaji
                english
                native
                }
                description
                season
                seasonYear
                format
                genres
                averageScore
                studios {
                    nodes {
                        name
                    }
                }
                coverImage {
                    large
                    extraLarge
                }
                bannerImage
                episodes
                trailer{
                    id
                    site
                    thumbnail
                }
            }
        }
        ';
        return $query;
    }
    /* Used by generateMassive Method 
     *
     */
    function saveAnimeThumbnail($item, $post)
    {
        if ($item->coverImage->extraLarge != null) {
            $client = new Client();
            $response = $client->get($item->coverImage->extraLarge);
            $imageContent = $response->getBody()->getContents();

            if (extension_loaded('gd')) {
                $imageContent = Image::make($imageContent)->encode('webp', 100); //->resize(150, 212)
                $file_name = Str::slug($post->slug) . '-' . time() . '.webp';
            } else {
                $file_name = Str::slug($post->slug) . '-' . time() . '.' . 'png';
            }
            $path = 'thumbnails/' . $file_name;
            $this->storeSingleImage($path, $imageContent);
            $post->thumbnail = $path;
            $post->thumbnail_src = $item->coverImage->extraLarge;
        }
        return $post;
    }
    /* Used by generateMassive Method 
     *
     */
    function saveAnimeBanner($item, $post)
    {
        if ($item->bannerImage != null) {

            $client = new Client();
            $response = $client->get($item->bannerImage);
            $imageContent = $response->getBody()->getContents();

            if (extension_loaded('gd')) {
                $file_name = Str::slug($post->slug) . '-' . time() . '.' . 'webp';
                $imageContent = Image::make($imageContent)->encode('webp', 100); //->resize(150, 212)
            } else {
                $file_name = Str::slug($post->slug) . '-' . time() . '.' . 'png';
            }
            $path = 'anime_banner/' . $file_name;
            $this->storeSingleImage($path, $imageContent);
            $post->banner = $path;
            $post->banner_src = $item->bannerImage;
        }
        return $post;
    }

    /* Used by Store and Update Method 
     *
     */
    public function storePostImages($post, $request)
    {
        /* Thumnail with file store */
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

            $imageContent = $request->file;

            if (extension_loaded('gd')) {
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $imageContent = Image::make($request->file)->encode('webp', 100);
            } else {
                $file_extension = $request->file->extension();
                $file_name = Str::slug($request->title) . '-' . time() . '.' . $file_extension;
            }
            $path = 'thumbnails/' . $file_name;
            $this->storeSingleImage($path, $imageContent);
            $post->thumbnail = $path;
        } else {
            /* Thumbnail witn url store */
            if ($request->thumbnail_src != null) {

                $post->thumbnail_src = $request->thumbnail_src;

                $client = new Client();
                $response = $client->get($request->thumbnail_src);
                $imageContent = $response->getBody()->getContents();

                if (extension_loaded('gd')) {
                    $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                    $imageContent = Image::make($imageContent)->encode('webp', 100);
                } else {
                    $headers = $response->getHeaders();
                    $contentType = $headers['Content-Type'][0] ?? null;

                    $extension = match ($contentType) {
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif',
                        'image/webp' => 'webp',
                        default      => 'bin',
                    };

                    $file_name = Str::slug($request->title) . '-' . time() . '.' . $extension;
                }

                $path = 'thumbnails/' . $file_name;
                $this->storeSingleImage($path, $imageContent);
                $post->thumbnail = $path;
            } else {
                $request->flash();
                return Redirect::back()->with('error', "Post not created, thumbnail image not found");
            }
        }

        /* Banner with file store */
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

            $imageContent = $request->banner;

            if (extension_loaded('gd')) {
                $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                $imageContent = Image::make($request->banner)->encode('webp', 100);
            } else {
                $extension = $request->file->extension();
                $file_name = Str::slug($request->title) . '-' . time() . '.' . $extension;
            }
            $path = 'anime_banner/' . $file_name;
            $this->storeSingleImage($path, $imageContent);
            $post->banner = $path;
        } else {
            /* Bannter with url store */
            if ($request->banner_src != null) {

                $post->banner_src = $request->banner_src;

                $client = new Client();
                $response = $client->get($request->thumbnail_src);
                $imageContent = $response->getBody()->getContents();

                if (extension_loaded('gd')) {
                    $file_name = Str::slug($request->title) . '-' . time() . '.' . 'webp';
                    $imageContent = Image::make($imageContent)->encode('webp', 100);
                } else {
                    $headers = $response->getHeaders();
                    $contentType = $headers['Content-Type'][0] ?? null;
                    $extension = match ($contentType) {
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif',
                        'image/webp' => 'webp',
                        default      => 'bin', // Extensión por defecto si no se reconoce
                    };

                    $file_name = Str::slug($request->title) . '-' . time() . '.' . $extension;
                }
                $path = 'anime_banner/' . $file_name;
                $this->storeSingleImage($path, $imageContent);
                $post->banner = $path;
            }
        }
        return $post;
    }

    public function storeSingleImage($path, $imageContent)
    {
        Storage::disk('public')->put($path, $imageContent);
    }

    public function addSong($post_id)
    {
        $post = Post::find($post_id);
        $seasons = Season::all();
        $years = Year::all();
        $types = [
            ['name' => 'Opening', 'value' => '1'],
            ['name' => 'Ending', 'value' => '2'],
            ['name' => 'Insert', 'value' => '3']
        ];
        $artists = Artist::all();


        return view('admin.songs.create', compact('artists', 'types', 'post', 'seasons','years'));
    }

    public function songs($post_id)
    {
        //dd(true);
        $post = Post::with('songs')->find($post_id);

        $breadcrumb = Breadcrumb::generate([
            [
                'name' => 'Index',
                'url' => route('admin.posts.index'),
            ],
            [
                'name' => $post->title,
                'url' => route('posts.songs', $post->id),
            ],
        ]);


        return view('admin.songs.manage', compact('post', 'breadcrumb'));
    }
}
