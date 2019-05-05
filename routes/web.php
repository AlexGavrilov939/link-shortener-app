<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'UrlController@index')->name('url.index');
Route::post('addUrl', 'UrlController@addUrl')->name('url.add');
Route::post('editUrl', 'UrlController@editUrl')->name('url.edit');
Route::get('removeUrl/{url_id}', 'UrlController@remove')->name('url.remove');

Route::get('{code}', 'UrlController@redirect')->name('url.redirect');