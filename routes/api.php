<?php

use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/boutiques/proches', [MapController::class, 'nearestShop']);
