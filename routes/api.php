<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FetcherController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ViewController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('fetchResult', [FetcherController::class, 'fetchResult']);
Route::get('fetchResult/{game_date}', [FetcherController::class, 'fetchResult']);

Route::get('fetchGame', [FetcherController::class, 'fetchGame']);
Route::get('fetchOU', [FetcherController::class, 'fetchOU']);
Route::get('fetch7M', [FetcherController::class, 'fetch7M']);
Route::get('fetch7mLive', [FetcherController::class, 'fetch7mLive']);

Route::get('fetch-game', [FetcherController::class, 'fetchGame']);
Route::get('fetchResult', [FetcherController::class, 'fetchResult']);
Route::get('fetchResult/{game_date}', [FetcherController::class, 'fetchResult']);
Route::get('fetch-ou', [FetcherController::class, 'fetchOU']);

Route::get('similar', [CompareController::class, 'matchSimilarTeams']);



Route::get('/games',                                         [ViewController::class, 'fetch7mGames']);
Route::get('/games/{start_date}/{end_date}',                 [ViewController::class, 'fetch7mGames']);


Route::get('/correctscore-match',                                           [ViewController::class, 'getCorrectscoreMatch']);
Route::get('/correctscore-match/{start_date}/{end_date}',                   [ViewController::class, 'getCorrectscoreMatch']);
Route::get('/correctscore-match/{start_date}/{end_date}/{exclude7m}',       [ViewController::class, 'getCorrectscoreMatch']);


Route::get('/unmatched',                                           [ViewController::class, 'getUnmatched']);
Route::get('/unmatched/{start_date}/{end_date}',                   [ViewController::class, 'getUnmatched']);


