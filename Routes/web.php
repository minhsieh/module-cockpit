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

Route::prefix('cockpit')->name('cockpit.')->group(function() {
    // Index
    Route::get('/', 'CockpitController@index');

    // Auth Using Routes
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('logout', function(){
        Auth::logout();
        return redirect('/cockpit');
    });

    // Admin
    Route::prefix('admin')->middleware('cockpit.auth')->group(function() {
        Route::get('/' , 'Admin\IndexController@index');
        Route::resource('users','Admin\UserController');
        Route::resource('teams','Admin\TeamController');
        Route::resource('permissions','Admin\PermissionController');
        Route::resource('roles','Admin\RoleController');
        Route::get('logs','Admin\LogViewerController@index');

        Route::get('teams/members/memberlist', 'Admin\TeamMemberController@memberlist')->name('teams.members.memberlist');
        Route::get('teams/members/{id}', 'Admin\TeamMemberController@show')->name('teams.members.show');
        Route::get('teams/members/resend/{invite_id}', 'Admin\TeamMemberController@resendInvite')->name('teams.members.resend_invite');
        Route::post('teams/members/{id}', 'Admin\TeamMemberController@invite')->name('teams.members.invite');
        Route::delete('teams/members/{id}/{user_id}', 'Admin\TeamMemberController@destroy')->name('teams.members.destroy');
        Route::post('teams/members/{id}/add', 'Admin\TeamMemberController@addMember')->name('teams.members.addmember');
        Route::post('teams/members/{id}/setowner', 'Admin\TeamMemberController@setOwner')->name('teams.members.setowner');
        //Route::get('teams/accept/{token}', 'Admin\AuthController@acceptInvite')->name('teams.accept_invite');
    });
    Route::get('teams/invite/accept/{token}', 'Auth\TeamInviteController@acceptInvite');

    
});

