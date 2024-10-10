<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeralController;

Route::get('/', [GeralController::class,'home']);

