<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'/user', 'middleware'=>['web']],function () {

    Route::post('/login', [
        'uses' => 'Auth\LoginController@login',
    ]);

    Route::get('/logout', [
        'uses' => 'Auth\LoginController@logout',
    ]);

});

Route::group(['prefix'=>'/temp'],function () {
    Route::post('/storeTempPic',[
        'uses' => 'TempController@storeTempPic',
    ]);

    Route::post('/storeTempDoc',[
        'uses' => 'TempController@storeTempDoc',
    ]);
});

Route::group(['prefix'=>'/auth', 'middleware'=>['web']],function () {

    Route::post('/login', [
        'uses' => 'AuthController@login',
    ]);

    Route::get('/logout', [
        'uses' => 'AuthController@logout',
    ]);

    Route::post('/signInWithGoogle', [
        'uses' => 'AuthController@signInWithGoogle',
    ]);

    Route::post('/signInWithFacebook', [
        'uses' => 'AuthController@signInWithFacebook',
    ]);

});

Route::group(['prefix'=>'/gym-membership', 'middleware'=>['auth.jwt']],function () {

    Route::post('/joinGym', [
        'uses' => 'GymMembershipController@joinGym',
    ]);

    Route::post('/leaveGym', [
        'uses' => 'GymMembershipController@leaveGym',
    ]);
});

Route::group(['prefix'=>'/appinvites', 'middleware'=>['auth.jwt']],function () {

    Route::post('/searchForFriends', [
        'uses' => 'AppInvitesController@searchForFriends',
    ]);

    Route::post('/commitAppInvites', [
        'uses' => 'AppInvitesController@commitAppInvites',
    ]);
});

\App\Util\CRUD\RouteUtils::dynamicAddRoutes('/user','UserController',['web']);