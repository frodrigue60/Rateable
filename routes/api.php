<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PostController as apiPostController;
use App\Http\Controllers\api\VideoController as apiVideoController;
use App\Http\Controllers\Api\SongVariantController as apiSongVarianCrontroller;



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

Route::controller(apiPostController::class)->group(function () {
    Route::post('/search', 'search');
});

Route::controller(apiSongVarianCrontroller::class)->group(function () {
    Route::post('/seasonal', 'seasonal');
    Route::post('/ranking', 'ranking');
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('variants', apiSongVarianCrontroller::class);
    Route::post('variants/{variant}/like', [apiSongVarianCrontroller::class, 'like'])->name('api.variants.like');
    Route::post('variants/{variant}/dislike', [apiSongVarianCrontroller::class, 'dislike'])->name('api.variants.dislike');
    Route::post('variants/{variant}/favorite', [apiSongVarianCrontroller::class, 'toggleFavorite'])->name('api.variants.favorite');
    Route::post('variants/{variant}/rate', [apiSongVarianCrontroller::class, 'rate'])->name('api.variants.rate');
});
