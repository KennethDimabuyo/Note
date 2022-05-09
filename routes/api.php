<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::get('authenticated', function () {
        return true;
    });

    Route::prefix('notes')->group(function() {
        Route::get('', 'NoteController@all');
        Route::get('{id}', 'NoteController@get');
        Route::post('{id?}', 'NoteController@createOrUpdate');
        Route::delete('{id}', 'NoteController@delete');
    });
});

Route::post('login', 'AuthController@Login');
Route::post('register', 'AuthController@Register');
