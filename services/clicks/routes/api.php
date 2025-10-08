<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\ForwardController;


Route::post('webhooks/click', [WebhookController::class, 'store']);
Route::get('clicks/stats', [StatsController::class, 'period']);
Route::post('forward', [ForwardController::class, 'forward']);

