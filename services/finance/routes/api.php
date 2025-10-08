<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MetricsController;

Route::get('campaigns/{id}/metrics/daily', [MetricsController::class, 'daily']);

