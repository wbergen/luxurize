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
        Route::get('tags/{tag_id}', [\App\Http\Controllers\ProductController::class, 'tagView'])->whereNumber('tag_id');
        Route::get('categories/{category_id}', [\App\Http\Controllers\ProductController::class, 'categoryView'])->whereNumber('category_id');
        Route::get('{id}', [\App\Http\Controllers\ProductController::class, 'detailView'])->whereNumber('id');
    });
Route::prefix('ajax')
    ->group(function() {
        Route::prefix('products')
            ->group(function() {
                Route::post('add-to-cart', [\App\Http\Controllers\ProductController::class, 'addToCart']);
            });

    });

Route::prefix('payments')
    ->group(function() {
        Route::post('process', [\App\Http\Controllers\PaymentController::class, 'process']);
    });


Route::get('checkout', function() {
    $gateway = new Braintree\Gateway([
        'environment' => config('payments.braintree.env'),
        'merchantId' => config('payments.braintree.id'),
        'publicKey' => config('payments.braintree.pub_key'),
        'privateKey' => config('payments.braintree.private_key')
    ]);
    return view('checkout', ['nonce' => $gateway->clientToken()->generate()]);
});

Route::get('thankyou', function() {
    return view('thankyou');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');






Route::middleware('auth')->group(function () {
    Route::get('/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

require __DIR__.'/auth.php';
