<?php

use App\Http\Controllers\SocialAcountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login/{provider}',[SocialAcountController::class,'redirectToProvider']);
Route::get('login/{provider}/callback',[SocialAcountController::class,'handleProviderCallback']);
