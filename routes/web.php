<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntityController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/{category}', [EntityController::class, 'getByCategory']);

Route::get('/fetch-data', [EntityController::class, 'fetchData']);