<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ItemController::class, 'index'])
    ->name('item.index');

Route::get('/item/{item}', [ItemController::class, 'show'])
    ->name('item.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sell', [ItemController::class, 'create'])
        ->name('item.create');

    Route::post('/sell', [ItemController::class, 'store'])
        ->name('item.store');

    Route::get(
        '/purchase/address/{item}',
        [ProfileController::class, 'editAddress']
    )->name('profile.address.edit');

    Route::post(
        '/purchase/address/{item}',
        [ProfileController::class, 'updateAddress']
    )->name('profile.address.update');

    Route::get(
        '/purchase/{item}',
        [OrderController::class, 'create']
    )->name('order.create');

    Route::post(
        '/purchase/{item}',
        [OrderController::class, 'store']
    )->name('order.store');

    Route::get(
        '/purchase/{item}/success',
        [OrderController::class, 'success']
    )->name('order.success');

    Route::get('/mypage', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get(
        '/mypage/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/mypage/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::post(
        '/item/{item}/comments',
        [CommentController::class, 'store']
    )->name('comment.store');

    Route::post(
        '/item/{item}/favorite',
        [FavoriteController::class, 'store']
    )->name('favorite.store');

    Route::delete(
        '/item/{item}/favorite',
        [FavoriteController::class, 'destroy']
    )->name('favorite.destroy');
});
