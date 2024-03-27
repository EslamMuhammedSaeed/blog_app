<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Route::get('/here', function () {
//     return view('app');
// })->name('application');
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::get('/', function () {
            return view('welcome');
        });
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::controller(FacebookController::class)->group(function () {
            Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
            Route::get('auth/facebook/callback', 'handleFacebookCallback');
        });

        Route::prefix('posts')->controller(PostController::class)->name("posts.")->group(function () {
            Route::post('/', 'store')->name('store');
            Route::post('/{post}', 'update')->name('update');
        });

        Route::prefix('comments')->controller(CommentController::class)->name("comments.")->group(function () {
            Route::post('/', 'store')->name('store');
            Route::post('/delete', 'destroy')->name('destroy');
        });
        Route::get('/js/lang.js', function () {
            // $strings = Cache::rememberForever('lang.js', function () {
            $lang = LaravelLocalization::getCurrentLocale();

            $files   = glob(resource_path('lang/' . $lang . '/*.php'));
            $strings = [];

            foreach ($files as $file) {
                $name           = basename($file, '.php');
                $strings[$name] = require $file;
            }

            //     return $strings;
            // });

            header('Content-Type: text/javascript');
            echo ('window.i18n = ' . json_encode($strings) . ';');
            exit();
        })->name('assets.lang');
    }
);



Auth::routes();
