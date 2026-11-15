<?php

declare(strict_types=1);

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

/**
 * WordPress routes.
 *
 * Nothing to declare here to render a template: the WordPress template
 * hierarchy resolves each request to the matching Blade view of the active
 * theme — home, single, page, archive, category, tag, author, date, search,
 * taxonomy, 404 — and falls back to index.blade.php.
 *
 * Use Route::wp() only when a request needs controller logic, middleware or a
 * named route.
 */

Route::group(['prefix' => 'account',], function () {
    Route::get('/sign-up', [UserController::class, 'signUp'])->name('account.sign-up');
    Route::post('/sign-up', [UserController::class, 'createUser'])->name('account.create-account');

    Route::get('/sign-in', [UserController::class, 'signIn'])->name('account.sign-in');
    Route::post('/sign-in', [UserController::class, 'authenticate'])->name('account.authenticate');

    Route::get('/', [UserController::class, 'account'])->name('account');
});

Route::post('/store-movie', [UserController::class, 'handleSaveMovie'])->name('store-movie');
