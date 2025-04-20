<?php

use App\Http\Controllers\Api\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('post',ImageController::class);
Route::post('post/update',[ImageController::class,'updatePost']);

