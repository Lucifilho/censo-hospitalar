<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeralController;
use App\Http\Controllers\PacienteController;

Route::get('/novo-paciente', [PacienteController::class,'novoPaciente']);
Route::post('/importar-planilha', [PacienteController::class, 'importarPlanilha']);
Route::post('/salvar-planilha', [PacienteController::class, 'salvarDadosPlanilha']);
Route::put('/atualizar-paciente', [PacienteController::class, 'update']);

Route::get('/pacientes', [PacienteController::class,'index']);
Route::get('/paciente-{codigo}', [PacienteController::class,'show']);
Route::get('/', [GeralController::class,'home']);
Route::get('/suporte', [GeralController::class,'suporte']);

