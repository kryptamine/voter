<?php

use App\Http\Controllers\PollController;
use App\Http\Controllers\PollVoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('polls', PollController::class)->only(['store', 'show']);
Route::apiResource('polls.votes', PollVoteController::class)->only(['store', 'index']);
