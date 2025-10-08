<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Parser\ParserController;

Route::post('/parse-url', [ParserController::class, 'parse']);
