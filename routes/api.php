<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PostController as apiPostController;
use App\Http\Controllers\Api\SongVariantController as apiSongVariantController;
use App\Http\Controllers\api\CommentController as apiCommentController;
use App\Http\Controllers\api\UserController as apiUserController;
use App\Http\Controllers\api\ArtistController as apiArtistController;
use App\Http\Controllers\api\SongController as apiSongController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

#POSTS
Route::resource('posts', apiPostController::class, ['as' => 'api']);
Route::post('/posts/search', [apiPostController::class, 'search'])->name('api.posts.search');
Route::get('/animes', [apiPostController::class, 'animes'])->name('api.posts.animes');

#SONGS
Route::resource('songs', apiSongController::class, ['as' => 'api']);
//Route::get('/themes', [apiSongController::class, 'themes'])->name('api.songs.filter');


#SONG VARIANTS
Route::resource('variants', apiSongVariantController::class, ['as' => 'api']);
Route::post('/seasonal', [apiSongVariantController::class, 'seasonal'])->name('api.variants.seasonal');
Route::post('/ranking', [apiSongVariantController::class, 'ranking'])->name('api.variants.ranking');
Route::get('/themes', [apiSongVariantController::class, 'filter'])->name('api.variants.filter');
Route::get('/variants/{variant}/comments', [apiSongVariantController::class, 'comments'])->name('api.variants.comments');

#ARTISTS
Route::resource('artists', apiArtistController::class, ['as' => 'api']);
Route::get('/artists/{artist}/themes', [apiArtistController::class, 'themes'])->name('api.artists.themes');


#AUTH ROUTES
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('variants/{variant}/like', [apiSongVariantController::class, 'like'])->name('api.variants.like');
    Route::post('variants/{variant}/dislike', [apiSongVariantController::class, 'dislike'])->name('api.variants.dislike');
    Route::post('variants/{variant}/favorite', [apiSongVariantController::class, 'toggleFavorite'])->name('api.variants.toggle.favorite');
    Route::post('variants/{variant}/rate', [apiSongVariantController::class, 'rate'])->name('api.variants.rate');

    Route::resource('comments', apiCommentController::class, ['as' => 'api']);

    Route::resource('users', apiUserController::class, ['as' => 'api']);
    Route::post('users/profile', [apiUserController::class, 'uploadAvatar'])->name('api.users.upload.avatar');
    Route::post('users/banner', [apiUserController::class, 'uploadBanner'])->name('api.users.upload.banner');
    Route::post('users/rating-system', [apiUserController::class, 'setRatingSystem'])->name('api.users.set.score');
});
