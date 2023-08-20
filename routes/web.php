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
Route::get('/animes',   [PostController::class, 'animes'])->name('animes');
Route::get('/anime/{id}/{slug}',   [PostController::class, 'show'])->name('anime.show');
Route::get('/post/{id}/{slug}',   [PostController::class, 'show'])->name('post.show');
Route::get('/song/{id}/{slug}/{suffix}',       [SongController::class, 'show'])->name('song.show');
Route::get('/openings',       [PostController::class, 'openings'])->name('openings');
Route::get('/endings',       [PostController::class, 'endings'])->name('endings');
Route::get('/seasonal-ranking',       [PostController::class, 'seasonalRanking'])->name('seasonal.ranking');
Route::get('/global-ranking',       [PostController::class, 'globalRanking'])->name('global.ranking');
Route::get('/themes', [PostController::class, 'themes'])->name('themes');

Route::get('/welcome',       [UserController::class, 'welcome'])->name('welcome');
Route::get('/user/{user}', [UserController::class, 'userList'])->name('user.list');

Route::get('/user/{user}', [UserController::class, 'userList'])->name('user.list');


Route::get('/offline', function () {
    return view('offline');
});

//ARTIST PUBLIC
Route::get('/artist/{id}/{slug}',    [ArtistController::class, 'show'])->name('artist.show');
Route::get('/artists',    [ArtistController::class, 'index'])->name('artist.index');


Route::group(['middleware' => 'staff'], function () {
    Route::prefix('admin')->group(function () {
        //SONGS
        Route::group(['middleware' => 'editor'], function () {
            Route::get('/song-post/{id}/create',       [AdminSongController::class, 'create'])->name('song.post.create');
            Route::post('/song-post/{id}/store',       [AdminSongController::class, 'store'])->name('song.post.store');
            
        });
        Route::group(['middleware' => 'editor'], function () {
            Route::get('/songs-post/{id}/manage',       [AdminSongController::class, 'manage'])->name('song.post.manage');
            Route::get('/songs-post/{id}/destroy',       [AdminSongController::class, 'destroy'])->name('song.post.destroy');
            Route::get('/songs-post/{id}/edit',       [AdminSongController::class, 'edit'])->name('song.post.edit');
            Route::put('/songs-post/{id}/update',       [AdminSongController::class, 'update'])->name('song.post.update');
        });
        //REQUESTS
        Route::group(['middleware' => 'creator'], function () {
            Route::get('/requests/index',       [AdminUserRequestController::class, 'index'])->name('admin.request.index');
            Route::get('/requests/{id}/destroy',       [AdminUserRequestController::class, 'destroy'])->name('admin.request.destroy');
            Route::get('/requests/{id}/show',       [AdminUserRequestController::class, 'show'])->name('admin.request.show');
        });
        //REPORTS
        Route::group(['middleware' => 'creator'], function () {
            Route::get('/reports/index',       [AdminReportController::class, 'index'])->name('admin.report.index');
        });
        Route::group(['middleware' => 'editor'], function () {
            Route::get('/report/{id}/fixed', [AdminReportController::class, 'fixed'])->name('admin.report.fixed');
            Route::get('/report/{id}/unfixed', [AdminReportController::class, 'unfixed'])->name('admin.report.unfixed');
            Route::get('/report/{id}/destroy', [AdminReportController::class, 'destroy'])->name('admin.report.destroy');
        });
        //POSTS
        Route::group(['middleware' => 'creator'], function () {
            Route::get('/post/index',       [AdminPostController::class, 'index'])->name('admin.post.index');
            Route::get('/post/create',      [AdminPostController::class, 'create'])->name('admin.post.create');
            Route::post('/post/store',      [AdminPostController::class, 'store'])->name('admin.post.store');
            Route::get('/post/{id}/show',   [AdminPostController::class, 'show'])->name('admin.post.show');
            Route::get('/post/search', [AdminPostController::class, 'search'])->name('admin.post.search');
        });
        Route::group(['middleware' => 'editor'], function () {
            Route::get('/post/{id}/edit',   [AdminPostController::class, 'edit'])->name('admin.post.edit');
            Route::put('/post/{id}/update', [AdminPostController::class, 'update'])->name('admin.post.update');
            Route::get('/post/{id}/destroy', [AdminPostController::class, 'destroy'])->name('admin.post.destroy');
            Route::post('/post/{id}/approve', [AdminPostController::class, 'approve'])->name('admin.post.approve');
            Route::post('/post/{id}/unapprove', [AdminPostController::class, 'unapprove'])->name('admin.post.unapprove');
        });
        Route::get('/search-animes', [AdminPostController::class, 'searchAnimes'])->name('search.animes')->middleware('admin');
        Route::get('/get-by-id', [AdminPostController::class, 'getById'])->name('get.by.id')->middleware('admin');
        Route::get('/get-seasonal-animes', [AdminPostController::class, 'getSeasonalAnimes'])->name('get.seasonal.animes')->middleware('admin');
        Route::get('/forceupdate', [AdminPostController::class, 'forceUpdate'])->name('forceupdate')->middleware('admin');
        Route::get('/wipe-all-posts', [AdminPostController::class, 'wipeAllPosts'])->name('wipeallposts')->middleware('admin');

        //TAGS
        Route::group(['middleware' => 'creator'], function () {
            Route::get('/tags/index',           [AdminTagController::class, 'index'])->name('admin.tags.index');
            Route::get('/tags/create',          [AdminTagController::class, 'create'])->name('admin.tags.create');
            Route::post('/tags/store',          [AdminTagController::class, 'store'])->name('admin.tags.store');
            Route::get('/tags/search', [AdminTagController::class, 'searchTag'])->name('search.tag');
        });
        Route::group(['middleware' => 'editor'], function () {
            Route::get('/tags/{id}/edit',       [AdminTagController::class, 'edit'])->name('admin.tags.edit');
            Route::put('/tags/{id}/update',    [AdminTagController::class, 'update'])->name('admin.tags.update');
            Route::get('/tags/{id}/destroy',    [AdminTagController::class, 'destroy'])->name('admin.tags.destroy');
        });
        Route::group(['middleware' => 'admin'], function () {
            Route::get('/tags/{id}/set',    [AdminTagController::class, 'set'])->name('admin.tags.set');
            Route::get('/tags/{id}/unset',    [AdminTagController::class, 'unset'])->name('admin.tags.unset');
        });

        //ARTISTS
        Route::group(['middleware' => 'creator'], function () {
            Route::get('/artist/create',          [AdminArtistController::class, 'create'])->name('admin.artist.create');
            Route::post('/artist/store',          [AdminArtistController::class, 'store'])->name('admin.artist.store');
            Route::get('/artist/index',           [AdminArtistController::class, 'index'])->name('admin.artist.index');
            Route::get('/artist/search', [AdminArtistController::class, 'searchArtist'])->name('admin.artist.search');
        });
        Route::group(['middleware' => 'editor'], function () {
            Route::get('/artist/{id}/destroy',    [AdminArtistController::class, 'destroy'])->name('admin.artist.destroy');
            Route::get('/artist/{id}/edit',       [AdminArtistController::class, 'edit'])->name('admin.artist.edit');
            Route::put('/artist/{id}/update',    [AdminArtistController::class, 'update'])->name('admin.artist.update');
        });

        //USERS
        Route::group(['middleware' => 'admin'], function () {
            Route::get('/users/create',          [AdminUserController::class, 'create'])->name('admin.users.create');
            Route::post('/users/store',          [AdminUserController::class, 'store'])->name('admin.users.store');
            Route::get('/users/index',           [AdminUserController::class, 'index'])->name('admin.users.index');
            Route::get('/users/{id}/destroy',    [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
            Route::get('/users/{id}/edit',       [AdminUserController::class, 'edit'])->name('admin.users.edit');
            Route::put('/users/{id}/update',    [AdminUserController::class, 'update'])->name('admin.users.update');
            Route::get('/users/search', [AdminUserController::class, 'searchUser'])->name('admin.users.search');

            Route::get('songs/{song_id}/videos',[AdminVideoController::class,'index'])->name('admin.videos.index');
            Route::get('songs/{song_id}/videos/create',[AdminVideoController::class,'create'])->name('admin.videos.create');
            Route::post('songs/{song_id}/videos/store',[AdminVideoController::class,'store'])->name('admin.videos.store');

            Route::get('videos/{video_id}/destroy',[AdminVideoController::class,'destroy'])->name('admin.videos.destroy');
            Route::get('videos/{video_id}/edit',[AdminVideoController::class,'edit'])->name('admin.videos.edit');
            Route::get('videos/{video_id}/show',[AdminVideoController::class,'show'])->name('admin.videos.show');
            Route::put('videos/{video_id}/update',[AdminVideoController::class,'update'])->name('admin.videos.update');
        });
    });
});

//AUTH ROUTES
Auth::routes();

//COMMENT LIKE
Route::post('/comment/{id}/like', [PostController::class, 'likeComment'])->name('comment.like');
Route::post('/comment/{ipostd}/unlike', [PostController::class, 'unlikeComment'])->name('comment.unlike');

//REQUEST ROUTES
Route::get('/request/create', [App\Http\Controllers\UserRequestController::class, 'create'])->name('request.create');
Route::post('/request/store', [App\Http\Controllers\UserRequestController::class, 'store'])->name('request.store');

//SONGS ROUTES
Route::post('/song/{id}/like', [SongController::class, 'likeSong'])->name('song.like');
Route::post('/song/{id}/unlike', [SongController::class, 'unlikeSong'])->name('song.unlike');
Route::post('/song/{id}/ratesong', [SongController::class, 'rateSong'])->name('song.addrate');

//USER ROUTES
Route::post('/change-score-format', [App\Http\Controllers\UserController::class, 'changeScoreFormat'])->name('change.score.format');
Route::get('/profile', [App\Http\Controllers\UserController::class, 'index'])->name('profile');
Route::post('/upload-profile-pic', [App\Http\Controllers\UserController::class, 'uploadProfilePic'])->name('upload.profile.pic');
Route::post('/upload-banner-pic', [App\Http\Controllers\UserController::class, 'uploadBannerPic'])->name('upload.banner.pic');
//POST ROUTES
Route::get('/favorites', [PostController::class, 'favorites'])->name('favorites');
Route::post('/post/{id}/like', [PostController::class, 'likePost'])->name('post.like');
Route::post('/post/{id}/unlike', [PostController::class, 'unlikePost'])->name('post.unlike');
Route::post('/post/{id}/ratepost', [PostController::class, 'ratePost'])->name('post.addrate');

Route::get('/song/{id}/report', [ReportController::class, 'createReport'])->name('song.create.report');
