<?php
use \App\Http\Controllers\backend\NewsController;
use \App\Http\Controllers\backend\ValidateController;
use \App\Http\Resources\backend\NewCollection;
use App\Models\News;


Route::get('/backend', [\App\Http\Controllers\backend\Main::class, 'index']);

Route::resource('/backend/news', NewsController::class);

Route::get('/backend/news_api', function () {
    return new NewCollection(News::all());
});

Route::post('/backend/validate', ValidateController::class);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/backend/logout', [\App\Http\Controllers\backend\Main::class, 'Logout']);

