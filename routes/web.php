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
Route::get('/show/{id}/{slug}-{suffix?}',   [PostController::class, 'show'])->name('post.show');
Route::get('/openings',       [PostController::class, 'openings'])->name('openings');
Route::get('/endings',       [PostController::class, 'endings'])->name('endings');
Route::get('/seasonal-ranking',       [PostController::class, 'seasonalRanking'])->name('seasonal.ranking');
Route::get('/global-ranking',       [PostController::class, 'globalRanking'])->name('global.ranking');
Route::get('/filter', [PostController::class, 'filter'])->name('filter');

Route::get('/welcome',       [UserController::class, 'welcome'])->name('welcome');
Route::get('/user/{user}', [UserController::class, 'userList'])->name('user.list');

Route::get('/offline', function () {
    return view('offline');
});

//ARTIST PUBLIC
Route::get('/artist/{slug}',    [ArtistController::class, 'show'])->name('artist.show');

Route::group(['middleware' => 'staff'], function () {
    Route::prefix('admin')->group(function () {
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
        Route::get('/forceupdate', [AdminPostController::class, 'forceUpdate'])->name('forceupdate')->middleware('admin');

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
        });
    });
});

//AUTH ROUTES
Auth::routes();

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
Route::get('/post/{id}/report', [ReportController::class, 'createReport'])->name('post.create.report');
