<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PostController as apiPostController;
use App\Http\Controllers\Api\SongVariantController as apiSongVariantController;
use App\Http\Controllers\api\CommentController as apiCommentController;
use App\Http\Controllers\api\UserController as apiUserController;
use App\Http\Controllers\api\ArtistController as apiArtistController;
use App\Http\Controllers\api\SongController as apiSongController;
use App\Http\Controllers\api\UserRequestController as apiUserRequestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

#POSTS
//Route::resource('posts', apiPostController::class);
Route::get('search/{q}', [apiPostController::class, 'search'])->name('api.posts.search');
Route::get('animes', [apiPostController::class, 'animes'])->name('api.posts.animes');

#SONGS
//Route::resource('songs', apiSongController::class);
Route::get('songs/seasonal', [apiSongController::class, 'seasonal'])->name('api.songs.seasonal');
Route::get('songs/ranking', [apiSongController::class, 'ranking'])->name('api.songs.ranking');
Route::get('songs/filter', [apiSongController::class, 'filter'])->name('api.songs.filter');


#SONG VARIANTS
//Route::resource('variants', apiSongVariantController::class);
Route::post('variants/seasonal', [apiSongVariantController::class, 'seasonal'])->name('api.variants.seasonal');
/* Route::post('variants/ranking', [apiSongVariantController::class, 'ranking'])->name('api.variants.ranking'); */
//Route::get('variants/filter', [apiSongVariantController::class, 'filter'])->name('api.variants.filter');
Route::get('variants/{variant}/comments', [apiSongVariantController::class, 'comments'])->name('api.variants.comments');
Route::get('variants/{variant}/get-videos', [apiSongVariantController::class, 'getVideos'])->name('api.variants.get-video');

#ARTISTS
//Route::resource('artists', apiArtistController::class);
Route::get('artists/{artist}/filter', [apiArtistController::class, 'songsFilter'])->name('api.artists.songs.filter');
Route::get('artists/filter', [apiArtistController::class, 'artistsFilter'])->name('api.artists.filter');

#USERS
Route::get('users', [apiUserController::class, 'index'])->name('api.users');
Route::get('users/{id}/list', [apiUserController::class, 'userList'])->name('api.users.list');
Route::get('users/{id}', [apiUserController::class, 'show'])->name('api.users.show');

#COMMENTS
Route::get('songs/{song}/comments', [apiSongController::class, 'comments'])->name('api.songs.comments');

#AUTH ROUTES
Route::middleware(['auth:sanctum'])->group(function () {
    #VARIANTS
    Route::post('variants/{variant}/like', [apiSongVariantController::class, 'like'])->name('api.variants.like');
    Route::post('variants/{variant}/dislike', [apiSongVariantController::class, 'dislike'])->name('api.variants.dislike');
    Route::post('variants/{variant}/favorite', [apiSongVariantController::class, 'toggleFavorite'])->name('api.variants.toggle.favorite');
    Route::post('variants/{variant}/rate', [apiSongVariantController::class, 'rate'])->name('api.variants.rate');

    #SONGS
    Route::get('songs/{song}/like', [apiSongController::class, 'like'])->name('api.songs.like');
    Route::get('songs/{song}/dislike', [apiSongController::class, 'dislike'])->name('api.songs.dislike');
    Route::get('songs/{song}/favorite', [apiSongController::class, 'toggleFavorite'])->name('api.songs.toggle.favorite');
    Route::post('songs/{song}/rate', [apiSongController::class, 'rate'])->name('api.songs.rate');
    Route::post('songs/comments', [apiSongController::class, 'storeComment'])->name('api.songs.store.comment');
    Route::post('songs/reports', [apiSongController::class, 'storeReport'])->name('api.songs.reports');

    #COMMENTS
    Route::resource('comments', apiCommentController::class, ['as' => 'api']);
    Route::get('comments/{id}/like', [apiCommentController::class, 'like'])->name('api.comments.like');
    Route::get('comments/{id}/dislike', [apiCommentController::class, 'dislike'])->name('api.comments.dislike');
    Route::post('comments/{parentComment}/reply', [apiCommentController::class, 'reply'])->name('comments.reply');

    #USER REQUESTS
    Route::resource('requests', apiUserRequestController::class, ['as' => 'api']);

    //Route::resource('users', apiUserController::class, ['as' => 'api']);
    Route::post('users/avatar', [apiUserController::class, 'uploadAvatar'])->name('api.users.upload.avatar');
    Route::post('users/banner', [apiUserController::class, 'uploadBanner'])->name('api.users.upload.banner');
    Route::post('users/score-format', [apiUserController::class, 'setScoreFormat'])->name('api.users.score.format');
    Route::post('users/favorites', [apiUserController::class, 'favorites'])->name('api.users.favorites');
});
