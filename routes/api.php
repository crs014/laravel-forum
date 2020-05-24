<?php

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

Route::get('/unauthorized', 'API\CommonController@unauthorized')->name('common.unauthorized');

Route::prefix('email')->group(function() {
    Route::get('/verify/{id}', 'API\VerificationApiController@verify')->name('verificationapi.verify');
    Route::get('/resend', 'API\VerificationApiController@resend')->name('verificationapi.resend');
});

Route::prefix('user')->group(function() {
    Route::post('/login', 'API\UserController@login')->name('user.login');
    Route::post('/register', 'API\UserController@register')->name('user.register');
});

Route::group(['middleware' => 'auth:api'], function (){
    
    Route::prefix('user')->group(function() {
        Route::get('/self', 'API\UserController@self')->name('user.self');    
    });

    Route::prefix('post')->group(function() {
        Route::get('/', 'API\PostController@index')->name('post.index');
        Route::post('/', 'API\PostController@store')->name('post.store');
        Route::get('/{id}', 'API\PostController@show')->name('post.show');
        Route::patch('/{id}', 'API\PostController@update')->name('post.update');
        Route::delete('/{id}', 'API\PostController@destroy')->name('post.destroy');
        Route::post('/{id}/comment', 'API\PostController@comment')->name('post.comment');
    });

    Route::prefix('comment')->group(function() {
        Route::get('/', 'API\CommentController@index')->name('comment.index');
        Route::delete('/{id}', 'API\CommentController@destroy')->name('comment.destroy');
    });

    Route::prefix('follow')->group(function() {
        Route::get('/followers', 'API\FollowController@followers')->name('follow.followers');
        Route::get('/following', 'API\FollowController@following')->name('follow.following');
        Route::post('/{id}', 'API\FollowController@following_user')->name('follow.following_user');
        Route::delete('/{id}', 'API\FollowController@destroy')->name('follow.destroy');
    });
});
