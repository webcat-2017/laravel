<?php
use App\Http\Controllers\frontend\MainController;

Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/new/{id}', [MainController::class, 'show'])->name('new');
