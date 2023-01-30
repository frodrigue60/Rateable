<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CurrentSeasonController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\api\UserController as apiUserController;
use App\Http\Controllers\UserController;

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
Route::get('/',       [PostController::class, 'home'])->name('/');
Route::get('/welcome',       [HomeController::class, 'welcome'])->name('welcome');
Route::get('/post/{id}/show',   [PostController::class, 'show'])->name('show');
Route::get('/show/{id}/{slug}',   [PostController::class, 'showBySlug'])->name('showbyslug');

Route::get('/openings',       [PostController::class, 'openings'])->name('openings');
Route::get('/endings',       [PostController::class, 'endings'])->name('endings');
Route::get('/seasonal-ranking',       [PostController::class, 'seasonalranking'])->name('seasonalranking');
Route::get('/global-ranking',       [PostController::class, 'globalrank'])->name('globalranking');
Route::get('/filter', [PostController::class, 'filter'])->name('filter');
Route::get('/user/{user}', [apiUserController::class, 'userList'])->name('userlist');


Route::get('/offline', function(){
    return view('offline');
});

//TAGS PUBLIC 
Route::get('/tags',          [TagController::class, 'alltags'])->name('tags');
Route::get('/tag/{slug}',           [TagController::class, 'tag_slug'])->name('fromtag');

//ARTIST PUBLIC
Route::get('/artist/{slug}',    [ArtistController::class, 'artist_slug'])->name('fromartist');

Route::group(['middleware' => 'admin.routes'], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/post/index',       [PostController::class, 'index'])->name('admin.post.index');
        Route::get('/post/create',      [PostController::class, 'create'])->name('admin.post.create');
        Route::get('/post/{id}/edit',   [PostController::class, 'edit'])->name('admin.post.edit');
        Route::put('/post/{id}/update', [PostController::class, 'update'])->name('admin.post.update');
        Route::get('/post/{id}/destroy', [PostController::class, 'destroy'])->name('admin.post.destroy');
        Route::post('/post/store',      [PostController::class, 'store'])->name('admin.post.store');
        Route::get('/post/{id}/show',   [PostController::class, 'show'])->name('admin.post.show');
        Route::get('/searchpost', [PostController::class, 'searchPost'])->name('searchpost');

        //TAGS
        Route::get('/tags/index',           [TagController::class, 'index'])->name('admin.tags.index');
        Route::get('/tags/create',          [TagController::class, 'create'])->name('admin.tags.create');
        Route::get('/tags/{id}/edit',       [TagController::class, 'edit'])->name('admin.tags.edit');
        Route::put('/tags/{id}/update',    [TagController::class, 'update'])->name('admin.tags.update');
        Route::get('/tags/{id}/destroy',    [TagController::class, 'destroy'])->name('admin.tags.destroy');
        Route::post('/tags/store',          [TagController::class, 'store'])->name('admin.tags.store');
        Route::get('/searchtag', [TagController::class, 'searchTag'])->name('searchtag');
        //END TAGS

        //CURRENT SEASON OP
        Route::get('/season/create',          [CurrentSeasonController::class, 'create'])->name('admin.season.create');
        Route::post('/season/store',          [CurrentSeasonController::class, 'store'])->name('admin.season.store');
        Route::get('/season/index',           [CurrentSeasonController::class, 'index'])->name('admin.season.index');
        Route::get('/season/{id}/destroy',    [CurrentSeasonController::class, 'destroy'])->name('admin.season.destroy');
        Route::get('/season/{id}/edit',       [CurrentSeasonController::class, 'edit'])->name('admin.season.edit');
        Route::put('/season/{id}/update',    [CurrentSeasonController::class, 'update'])->name('admin.season.update');

        //ARTISTS
        Route::get('/artist/create',          [ArtistController::class, 'create'])->name('admin.artist.create');
        Route::post('/artist/store',          [ArtistController::class, 'store'])->name('admin.artist.store');
        Route::get('/artist/index',           [ArtistController::class, 'index'])->name('admin.artist.index');
        Route::get('/artist/{id}/destroy',    [ArtistController::class, 'destroy'])->name('admin.artist.destroy');
        Route::get('/artist/{id}/edit',       [ArtistController::class, 'edit'])->name('admin.artist.edit');
        Route::put('/artist/{id}/update',    [ArtistController::class, 'update'])->name('admin.artist.update');
        //END ARTISTS 

        //START USERS
        Route::get('/users/create',          [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users/store',          [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/index',           [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{id}/destroy',    [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/users/{id}/edit',       [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}/update',    [UserController::class, 'update'])->name('admin.users.update');
        Route::get('/searchUser', [UserController::class, 'searchUser'])->name('searchuser');
    });
});

//AUTH ROUTES
Auth::routes();

//USER ROUTES
Route::post('/scoreformat', [App\Http\Controllers\HomeController::class, 'scoreFormat'])->name('scoreformat');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/upload', [App\Http\Controllers\HomeController::class, 'upload'])->name('upload');

Route::get('/favorites', [PostController::class, 'favorites'])->name('favorites');


Route::post('/upthumbnail', [App\Http\Controllers\PostController::class, 'upload'])->name('upthumbnail');

Route::post('/like-post/{id}', [PostController::class, 'likePost'])->name('like.post');
Route::post('/unlike-post/{id}', [PostController::class, 'unlikePost'])->name('unlike.post');
Route::post('/post/{id}/ratepost', [PostController::class, 'ratePost'])->name('post.addrate');
