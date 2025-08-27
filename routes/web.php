<?php

use App\Http\Controllers\GetAction;
use App\Http\Controllers\HomeAction;
use App\Http\Controllers\SearchAction;
use App\Http\Controllers\StatisticsAction;
use Illuminate\Support\Facades\Route;


Route::get('/', HomeAction::class)->name('home');
Route::post('/search', SearchAction::class)->name('search');
Route::get('/{type}/{id}', GetAction::class)->name('details');

Route::get('/statistics', StatisticsAction::class)->name('statistics');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
