<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\PostController as PostController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\TagController as TagController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\ArtistController as ArtistController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController as UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ReportController as ReportController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserRequestController as AdminUserRequestController;
use App\Http\Controllers\Admin\SongController as AdminSongController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\SongVariantController as AdminSongVariantController;
use App\Http\Controllers\SongVariantController as SongVariantController;
use App\Http\Controllers\FavoriteController as FavoriteController;
use App\Http\Controllers\Admin\YearController as AdminYearController;
use App\Http\Controllers\Admin\SeasonController as AdminSeasonController;
use App\Http\Controllers\CommentController as CommentController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\YearController;

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

/* Route::get('/', function () {
    return 'Aqui habia ponido mi sitio web';
})->name('/'); */

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

//ARTIST PUBLIC
Route::get('/artists/{artist}/{slug}',    [ArtistController::class, 'show'])->name('artists.show');
Route::get('/artists',    [ArtistController::class, 'index'])->name('artist.index');

Route::resource('years', YearController::class);
Route::resource('seasons', SeasonController::class);

Route::group(['middleware' => 'staff'], function () {
    Route::prefix('admin')->group(function () {
        //SONGS
        Route::resource('songs', AdminSongController::class,['as' => 'admin']);
        Route::get('songs/{song}/variants/add', [AdminSongController::class, 'addVariant'])->name('admin.songs.variants.add');
        Route::get('songs/{song}/variants', [AdminSongController::class, 'variants'])->name('admin.songs.variants');

        //VARIANTS
        Route::resource('variants', AdminSongVariantController::class,['as' => 'admin']);
        Route::get('/variants/{variant}/videos', [AdminSongVariantController::class, 'videos'])->name('admin.variants.videos');
        Route::get('/variants/{variant}/videos/add', [AdminSongVariantController::class, 'addVideos'])->name('admin.variants.videos.add');

        //VIDEOS
        Route::resource('videos', AdminVideoController::class,['as' => 'admin']);

        //REQUESTS
        Route::resource('requests', AdminUserRequestController::class,['as' => 'admin']);

        //REPORTS
        Route::resource('reports', AdminReportController::class,['as' => 'admin']);
        Route::get('/reports/{report}/toggle', [AdminReportController::class, 'toggleStatus'])->name('admin.reports.toggle');
        
        //POSTS
        Route::resource('posts', AdminPostController::class,['as' => 'admin']);
        Route::get('/posts/search', [AdminPostController::class, 'search'])->name('admin.posts.search');
        Route::post('/posts/{post}/approve', [AdminPostController::class, 'approve'])->name('admin.posts.approve');
        Route::post('/posts/{post}/unapprove', [AdminPostController::class, 'unapprove'])->name('admin.posts.unapprove');
        Route::get('/posts/{post}/songs/add', [AdminPostController::class, 'addSong'])->name('admin.posts.songs.add');
        Route::get('/posts/{post}/songs', [AdminPostController::class, 'songs'])->name('posts.songs');
        Route::get('/posts/search-animes', [AdminPostController::class, 'searchAnimes'])->name('admin.search.animes');
        Route::get('/posts/get-by-id', [AdminPostController::class, 'getById'])->name('get.by.id');
        Route::get('/posts/get-seasonal-animes', [AdminPostController::class, 'getSeasonalAnimes'])->name('get.seasonal.animes');
        Route::get('/posts/force-update', [AdminPostController::class, 'forceUpdate'])->name('force.update');
        Route::get('/posts/posts/wipe', [AdminPostController::class, 'wipePosts'])->name('posts.wipe');

        //ARTISTS
        Route::resource('artists', AdminArtistController::class, ['as' => 'admin']);
        Route::get('/artists/search', [AdminArtistController::class, 'searchArtist'])->name('admin.artists.search');

        //USERS
        Route::resource('users', AdminUserController::class, ['as' => 'admin']);
        Route::get('/users/search', [AdminUserController::class, 'searchUser'])->name('admin.users.search');

        //YEARS
        Route::resource('years', AdminYearController::class, ['as' => 'admin']);

        //SEASONS
        Route::resource('seasons', AdminSeasonController::class, ['as' => 'admin']);
    });
});

//AUTH ROUTES
Auth::routes();

//COMMENTS
Route::resource('comments', CommentController::class);
Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])->name('comments.dislike');

//REQUEST ROUTES
Route::get('/requests/create', [App\Http\Controllers\UserRequestController::class, 'create'])->name('request.create');
Route::post('/requests/store', [App\Http\Controllers\UserRequestController::class, 'store'])->name('request.store');

//USER ROUTES
Route::post('/change-score-format', [App\Http\Controllers\UserController::class, 'changeScoreFormat'])->name('change.score.format');
Route::get('/profile', [App\Http\Controllers\UserController::class, 'index'])->name('profile');
Route::post('/upload-profile-pic', [App\Http\Controllers\UserController::class, 'uploadProfilePic'])->name('upload.profile.pic');
Route::post('/upload-banner-pic', [App\Http\Controllers\UserController::class, 'uploadBannerPic'])->name('upload.banner.pic');

//Variants ROUTES
Route::get('/favorites', [UserController::class, 'favorites'])->name('favorites');

//REPORTS
Route::post('reports/store', [ReportController::class, 'store'])->name('reports.store');

//SONG VARIANT ROUTES
Route::post('/variant/{variant}/rate', [SongVariantController::class, 'rate'])->name('variant.rate');
Route::post('variants/{variant}/like', [App\Http\Controllers\SongVariantController::class, 'like'])->name('variants.like');
Route::post('variants/{variant}/dislike', [App\Http\Controllers\SongVariantController::class, 'dislike'])->name('variants.dislike');
Route::post('variants/{variant}/favorite', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
