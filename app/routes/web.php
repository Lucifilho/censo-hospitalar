<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeralController;
use App\Http\Controllers\PacienteController;


Route::get('/', [GeralController::class,'home']);
Route::get('/lista-pacientes', [PacienteController::class,'index']);
Route::get('/novo-paciente', [PacienteController::class,'novoPaciente']);
Route::get('/suporte', [GeralController::class,'suporte']);

