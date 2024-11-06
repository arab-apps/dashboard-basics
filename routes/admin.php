<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\HomepageDashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('language/{locale}', function ($locale) {
    Session::put('lang', $locale);

    return redirect()->back();
})->name('lang');



Route::get('un-authorized-page', function () {

    return view('admin.errors.not_authorized');
})->name('un_authorized');

Route::get('login',[AuthenticateController::class,'loginForm'])->name('admin-login-form')->middleware('localization');
Route::post('login',[AuthenticateController::class,'login'])->name('admin.login');
// Route::get('/',[DashboardHomepageController::class,'dashboardIndex'])->name('admin.dashboard');

Route::group(['middleware'=>['checkAdmin','localization']],function(){
    Route::get('/',[DashboardHomepageController::class,'dashboardIndex'])->name('admin.dashboard');
    Route::get('admin_logout',[AuthenticateController::class,'admin_logout'])->name('admin.logout');
});
// Route::get('/',[HomepageDashboardController::class,'index'])->name('admin.home');

