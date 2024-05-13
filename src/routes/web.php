<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function(){
	Route::post('payu/local/verify','Nksquare\Payu\Controllers\LocalController@verify')->name('payu.local.verify');
	Route::get('payu/local/payment','Nksquare\Payu\Controllers\LocalController@payment')->name('payu.local.payment');
});