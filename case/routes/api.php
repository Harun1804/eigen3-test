<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\MemberController;

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

Route::prefix('members')->name('members.')->group(function(){
    Route::get('all',[MemberController::class,'getAllMembers'])->name('all');
});

Route::prefix('books')->name('books.')->group(function(){
    Route::get('all',[BookController::class,'getAllBooks'])->name('all');
    Route::post('borrowed',[BookController::class,'postBorrowedBook'])->name('borrowed');
    Route::post('returned',[BookController::class,'postReturnedBook'])->name('returned');
});
