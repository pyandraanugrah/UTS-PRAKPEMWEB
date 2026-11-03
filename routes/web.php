
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

// Menampilkan seluruh menu
Route::get('/menu', [MenuController::class, 'index'])
    ->name('menu.index');

// Menambahkan menu
Route::post('/menu', [MenuController::class, 'store'])
    ->name('menu.store');

// Menampilkan halaman edit
Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])
    ->name('menu.edit');

// Memperbarui menu
Route::put('/menu/{id}', [MenuController::class, 'update'])
    ->name('menu.update');

// Menghapus menu
Route::delete('/menu/{id}', [MenuController::class, 'destroy'])
    ->name('menu.destroy');
