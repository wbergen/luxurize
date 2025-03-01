<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [\App\Http\Controllers\ProductController::class, 'listView']);
Route::prefix('products')
    ->group(function() {
        Route::get('{id}', [\App\Http\Controllers\ProductController::class, 'detailView'])->whereNumber('id');
    });
Route::prefix('ajax')
    ->group(function() {
        Route::prefix('products')
            ->group(function() {
                Route::post('add-to-cart', [\App\Http\Controllers\ProductController::class, 'addToCart']);
            });

    });




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');






Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

require __DIR__.'/auth.php';
