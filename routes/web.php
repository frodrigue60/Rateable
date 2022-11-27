<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CurrentSeasonController;


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

Route::get('/',       [PostController::class, 'home'])->name('/');
Route::get('/endings',       [PostController::class, 'endings'])->name('endings');

Route::get('/search', [PostController::class, 'search'])->name('search');

//TAGS PUBLIC 
Route::get('/tags',          [TagController::class, 'alltags'])->name('tags');
Route::get('/tag/{slug}',           [TagController::class, 'slug'])->name('fromtag');

//POST PUBLIC
Route::get('/post/{id}/show',   [PostController::class, 'show'])->name('show');


Route::group(['middleware' => 'auth'], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/post/index',       [PostController::class, 'index'])->name('admin.post.index');
        Route::get('/post/create',      [PostController::class, 'create'])->name('admin.post.create');
        Route::get('/post/{id}/edit',   [PostController::class, 'edit'])->name('admin.post.edit');
        Route::put('/post/{id}/update', [PostController::class, 'update'])->name('admin.post.update');
        Route::get('/post/{id}/destroy', [PostController::class, 'destroy'])->name('admin.post.destroy');
        Route::post('/post/store',      [PostController::class, 'store'])->name('admin.post.store');
        Route::get('/post/{id}/show',   [PostController::class, 'show'])->name('admin.post.show');


        //TAGS
        Route::get('/tags/create',          [TagController::class, 'create'])->name('admin.tags.create');
        Route::post('/tags/store',          [TagController::class, 'store'])->name('admin.tags.store');
        Route::get('/tags/index',           [TagController::class, 'index'])->name('admin.tags.index');
        Route::get('/tags/{id}/destroy',    [TagController::class, 'destroy'])->name('admin.tags.destroy');
        Route::get('/tags/{id}/edit',       [TagController::class, 'edit'])->name('admin.tags.edit');
        Route::put('/tags/{id}/update',    [TagController::class, 'update'])->name('admin.tags.update');
        //END TAGS

        //CURRENT SEASON OP
        Route::get('/season/create',          [CurrentSeasonController::class, 'create'])->name('admin.season.create');
        Route::post('/season/store',          [CurrentSeasonController::class, 'store'])->name('admin.season.store');
        Route::get('/season/index',           [CurrentSeasonController::class, 'index'])->name('admin.season.index');
        Route::get('/season/{id}/destroy',    [CurrentSeasonController::class, 'destroy'])->name('admin.season.destroy');
        Route::get('/season/{id}/edit',       [CurrentSeasonController::class, 'edit'])->name('admin.season.edit');
        Route::put('/season/{id}/update',    [CurrentSeasonController::class, 'update'])->name('admin.season.update');
    });
});

//AUTH ROUTES
Auth::routes();

Route::get('/searchpost', [PostController::class, 'searchPost'])->name('searchpost');
Route::get('/searchtag', [TagController::class, 'searchTag'])->name('searchtag');

Route::get('/favorites', [PostController::class, 'favorites'])->name('favorites');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/like-post/{id}', [PostController::class, 'likePost'])->name('like.post');
Route::post('/unlike-post/{id}', [PostController::class, 'unlikePost'])->name('unlike.post');
Route::post('/post/{id}/ratepost', [PostController::class, 'ratePost'])->name('post.addrate');
