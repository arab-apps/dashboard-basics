<?php

use App\Http\Controllers\Api\HomePageController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1', 'middleware' => ['setLocalLang', 'app-active', 'client-valid']], function () {
    // dd(request()->header('lang'));
    Route::get('/test',[HomePageController::class,'index']);
});