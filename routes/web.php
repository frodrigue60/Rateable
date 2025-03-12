<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController as PostController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TagController as TagController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\ArtistController as ArtistController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController as UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ReportController as ReportController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\UserRequestController as UserRequestController;
use App\Http\Controllers\Admin\UserRequestController as AdminUserRequestController;
use App\Http\Controllers\Admin\SongController as AdminSongController;
use App\Http\Controllers\SongController as SongController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\SongVariantController as AdminSongVariantController;
use App\Http\Controllers\SongVariantController as SongVariantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//POST PUBLIC
Route::get('/',       [PostController::class, 'index'])->name('/');

Route::get('/openings',       [PostController::class, 'openings'])->name('openings');
Route::get('/endings',       [PostController::class, 'endings'])->name('endings');
Route::get('/seasonal-ranking',       [PostController::class, 'seasonalRanking'])->name('seasonal.ranking');
Route::get('/global-ranking',       [PostController::class, 'globalRanking'])->name('global.ranking');
Route::get('/themes', [PostController::class, 'themes'])->name('themes');
Route::get('/welcome',       [UserController::class, 'welcome'])->name('welcome');
Route::get('/user/{user}', [UserController::class, 'userList'])->name('user.list');

Route::get('/animes',   [PostController::class, 'animes'])->name('animes');
Route::get('/anime/{slug}',   [PostController::class, 'show'])->name('post.show');
Route::get('/anime/{anime_slug}/{song_slug}/v{variant_version_number}', [SongVariantController::class, 'show'])->name('variants.show');

Route::get('/offline', function () {
    return view('offline');
});

//Route::get('/anime/{anime}/{slug}',   [PostController::class, 'show'])->name('anime.show');
//Route::get('/song/{song}/{slug}/{suffix}',       [SongController::class, 'show'])->name('song.show');

//ARTIST PUBLIC
Route::get('/artists/{artist}/{slug}',    [ArtistController::class, 'show'])->name('artists.show');
Route::get('/artists',    [ArtistController::class, 'index'])->name('artist.index');


Route::group(['middleware' => 'staff'], function () {
    Route::prefix('admin')->group(function () {
        //SONGS
        Route::get('/posts/{post}/songs/create', [AdminSongController::class, 'create'])->name('posts.songs.create');
        Route::post('/posts/{post}/songs/store', [AdminSongController::class, 'store'])->name('posts.songs.store');
        Route::get('/posts/{post}/songs', [AdminSongController::class, 'manage'])->name('posts.songs');

        Route::get('/posts/songs/{song}/destroy', [AdminSongController::class, 'destroy'])->name('posts.songs.destroy');
        Route::get('/posts/songs/{song}/edit', [AdminSongController::class, 'edit'])->name('posts.songs.edit');
        Route::put('/posts/songs/{song}/update', [AdminSongController::class, 'update'])->name('posts.songs.update');


        //VARIANTS
        Route::get('songs/{song}/variants/create', [AdminSongVariantController::class, 'addVariant'])->name('songs.variants.add');
        Route::get('songs/{song}/variants', [AdminSongVariantController::class, 'manage'])->name('songs.variants.manage');

        Route::get('/variants/{variant}/destroy', [AdminSongVariantController::class, 'destroy'])->name('songs.variants.destroy');
        Route::get('/variants/{variant}/edit', [AdminSongVariantController::class, 'edit'])->name('songs.variants.edit');
        Route::get('/variants/{variant}/show', [AdminSongVariantController::class, 'show'])->name('songs.variants.show');
        Route::post('/variants/{variant}/update', [AdminSongVariantController::class, 'update'])->name('song.variant.update');
        

        //VIDEOS
        Route::get('/variants/{variant}/videos', [AdminVideoController::class, 'manage'])->name('variant.videos.manage');
        Route::get('/variants/{variant}/videos/index', [AdminVideoController::class, 'index'])->name('admin.videos.index');
        Route::get('/variants/{variant}/videos/create', [AdminVideoController::class, 'create'])->name('admin.videos.create');
        Route::post('/variants/{variant}/videos/store', [AdminVideoController::class, 'store'])->name('admin.videos.store');

        Route::get('/videos/{video}/destroy', [AdminVideoController::class, 'destroy'])->name('admin.videos.destroy');
        Route::get('/videos/{video}/edit', [AdminVideoController::class, 'edit'])->name('admin.videos.edit');
        Route::get('/videos/{video}/show', [AdminVideoController::class, 'show'])->name('admin.videos.show');
        Route::put('/videos/{video}/update', [AdminVideoController::class, 'update'])->name('admin.videos.update');


        Route::get('/variants/{variant}/video/create', [AdminVideoController::class, 'create'])->name('variants.video.create');
        Route::post('/variants/{variant}/video/store', [AdminVideoController::class, 'store'])->name('variants.video.store');


        //REQUESTS
        Route::get('/requests',       [AdminUserRequestController::class, 'index'])->name('admin.requests.index');
        Route::get('/requests/{request}/destroy',       [AdminUserRequestController::class, 'destroy'])->name('admin.request.destroy');
        Route::get('/requests/{request}/show',       [AdminUserRequestController::class, 'show'])->name('admin.request.show');

        //REPORTS

        Route::get('/reports',       [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/{report}/fixed', [AdminReportController::class, 'fixed'])->name('admin.report.fixed');
        Route::get('/reports/{report}/unfixed', [AdminReportController::class, 'unfixed'])->name('admin.report.unfixed');
        Route::get('/reports/{report}/destroy', [AdminReportController::class, 'destroy'])->name('admin.report.destroy');

        //POSTS

        Route::get('/posts',       [AdminPostController::class, 'index'])->name('admin.posts.index');
        Route::get('/posts/create',      [AdminPostController::class, 'create'])->name('admin.posts.create');
        Route::post('/posts/store',      [AdminPostController::class, 'store'])->name('admin.posts.store');
        Route::get('/posts/{post}/show',   [AdminPostController::class, 'show'])->name('admin.posts.show');
        Route::get('/posts/{post}/edit',   [AdminPostController::class, 'edit'])->name('admin.posts.edit');
        Route::put('/posts/{post}/update', [AdminPostController::class, 'update'])->name('admin.posts.update');
        Route::get('/posts/{post}/destroy', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');

        Route::get('/posts/search', [AdminPostController::class, 'search'])->name('admin.posts.search');
        Route::post('/posts/{post}/approve', [AdminPostController::class, 'approve'])->name('admin.posts.approve');
        Route::post('/posts/{post}/unapprove', [AdminPostController::class, 'unapprove'])->name('admin.posts.unapprove');


        Route::get('/search-animes', [AdminPostController::class, 'searchAnimes'])->name('admin.search.animes');
        Route::get('/get-by-id', [AdminPostController::class, 'getById'])->name('get.by.id');
        Route::get('/get-seasonal-animes', [AdminPostController::class, 'getSeasonalAnimes'])->name('get.seasonal.animes');
        Route::get('/force-update', [AdminPostController::class, 'forceUpdate'])->name('force.update');
        Route::get('/posts/wipe', [AdminPostController::class, 'wipePosts'])->name('posts.wipe');

        //TAGS

        Route::get('/tags/index',           [AdminTagController::class, 'index'])->name('admin.tags.index');
        Route::get('/tags/create',          [AdminTagController::class, 'create'])->name('admin.tags.create');
        Route::post('/tags/store',          [AdminTagController::class, 'store'])->name('admin.tags.store');
        Route::get('/tags/search', [AdminTagController::class, 'search'])->name('admin.tags.search');


        Route::get('/tags/{tag}/edit',       [AdminTagController::class, 'edit'])->name('admin.tags.edit');
        Route::put('/tags/{tag}/update',    [AdminTagController::class, 'update'])->name('admin.tags.update');
        Route::get('/tags/{tag}/destroy',    [AdminTagController::class, 'destroy'])->name('admin.tags.destroy');


        Route::get('/tags/{tag}/set',    [AdminTagController::class, 'set'])->name('admin.tags.set');
        Route::get('/tags/{tag}/unset',    [AdminTagController::class, 'unset'])->name('admin.tags.unset');


        //ARTISTS

        Route::get('/artists/create',          [AdminArtistController::class, 'create'])->name('admin.artist.create');
        Route::post('/artists/store',          [AdminArtistController::class, 'store'])->name('admin.artist.store');
        Route::get('/artists/index',           [AdminArtistController::class, 'index'])->name('admin.artist.index');
        Route::get('/artists/search', [AdminArtistController::class, 'searchArtist'])->name('admin.artist.search');


        Route::get('/artists/{artist}/destroy',    [AdminArtistController::class, 'destroy'])->name('admin.artist.destroy');
        Route::get('/artists/{artist}/edit',       [AdminArtistController::class, 'edit'])->name('admin.artist.edit');
        Route::put('/artists/{artist}/update',    [AdminArtistController::class, 'update'])->name('admin.artist.update');


        //USERS

        Route::get('/users/create',          [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users/store',          [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/index',           [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}/destroy',    [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/users/{user}/edit',       [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}/update',    [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::get('/users/search', [AdminUserController::class, 'searchUser'])->name('admin.users.search');
    });
});

//AUTH ROUTES
Auth::routes();

//COMMENT LIKE
Route::post('/comment/{comment}/like', [PostController::class, 'likeComment'])->name('comment.like');
Route::post('/comment/{comment}/unlike', [PostController::class, 'unlikeComment'])->name('comment.unlike');

//REQUEST ROUTES
Route::get('/requests/create', [App\Http\Controllers\UserRequestController::class, 'create'])->name('request.create');
Route::post('/requests/store', [App\Http\Controllers\UserRequestController::class, 'store'])->name('request.store');

//SONGS ROUTES

//USER ROUTES
Route::post('/change-score-format', [App\Http\Controllers\UserController::class, 'changeScoreFormat'])->name('change.score.format');
Route::get('/profile', [App\Http\Controllers\UserController::class, 'index'])->name('profile');
Route::post('/upload-profile-pic', [App\Http\Controllers\UserController::class, 'uploadProfilePic'])->name('upload.profile.pic');
Route::post('/upload-banner-pic', [App\Http\Controllers\UserController::class, 'uploadBannerPic'])->name('upload.banner.pic');
//POST ROUTES
Route::get('/favorites', [PostController::class, 'favorites'])->name('favorites');
//Route::post('/post/{post}/like', [PostController::class, 'likePost'])->name('post.like');
//Route::post('/post/{post}/unlike', [PostController::class, 'unlikePost'])->name('post.unlike');
//Route::post('/post/{post}/ratepost', [PostController::class, 'ratePost'])->name('post.addrate');

Route::get('variant/{song_variant_id}/report', [ReportController::class, 'createReport'])->name('variant.report.create');

//SONG VARIANT ROUTES
Route::post('/variant/{variant}/unlike', [SongVariantController::class, 'unlikeVariant'])->name('variant.unlike');
Route::post('/variant/{variant}/like', [SongVariantController::class, 'likeVariant'])->name('variant.like');
Route::post('/variant/{variant}/ratepost', [SongVariantController::class, 'rate'])->name('variant.rate');
