<?php

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

Route::prefix('cockpit')->group(function() {
    Route::get('/', 'CockpitController@index');
    Route::auth();
    Route::get('logout', function(){
        Auth::logout();
        return redirect('/cockpit');
    });

    Route::prefix('admin')->group(function() {
        Route::get('/' , 'Admin\IndexController@index');
        Route::resource('users','Admin\UserController');
        Route::resource('teams','Admin\TeamController');
        Route::get('logs','Admin\LogViewerController@index');
    });

    
});

