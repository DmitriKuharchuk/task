<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DIExampleController;

Route::get('/test-di', [DIExampleController::class, 'index']);
