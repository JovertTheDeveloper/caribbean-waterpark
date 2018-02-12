<?php

Route::group(['namespace' => 'Root', 'prefix' => 'superuser', 'as' => 'root.'], function () {

    Route::group(['namespace' => 'Auth'], function() {
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login');
        Route::post('logout', 'LoginController@logout')->name('logout');

        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset/{token}', 'ResetPasswordController@reset');
    });

    Route::middleware('root.auth')->group(function() {
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource('superusers', 'UsersController');

        Route::resource('categories', 'CategoriesController');
        Route::get('categories/{id}/image', 'CategoriesController@selectImage')->name('categories.image');
        Route::post('categories/{id}/image/upload', 'CategoriesController@uploadImage')->name('categories.image.upload');
        Route::get('categories/{id}/image/uploaded', 'CategoriesController@uploadedImage')->name('categories.image.uploaded');
        Route::delete('categories/{id}/image/destroy', 'CategoriesController@destroyImage')->name('categories.image.destroy');

        Route::resource('items', 'ItemsController');
        Route::resource('coupons', 'CouponsController');
    });
});