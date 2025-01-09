<?php

use App\Http\Controllers\Lokasyon\IlKontrolYonetimi;
use App\Http\Controllers\ModalPositionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/save-modal-position', [ModalPositionController::class, 'save']);
Route::get('/load-modal-position', [ModalPositionController::class, 'load']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('il-yonetimi')->group(function () {
        Route::get('/latest-kod', [IlKontrolYonetimi::class, 'latestKod'])->name('il.latest-kod');
        Route::get('/', [IlKontrolYonetimi::class, 'index'])->name('il.index');
        Route::post('/', [IlKontrolYonetimi::class, 'store'])->name('il.store');
        Route::get('/{id}', [IlKontrolYonetimi::class, 'show'])->name('il.show');
        Route::put('/{id}', [IlKontrolYonetimi::class, 'update'])->name('il.update');
        Route::delete('/{id}', [IlKontrolYonetimi::class, 'destroy'])->name('il.destroy');
    });
});