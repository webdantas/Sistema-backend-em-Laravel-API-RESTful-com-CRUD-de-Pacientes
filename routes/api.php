<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//API route for register new user
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
// ->middleware('consulta-cep');
//API route for login user
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);

    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    Route::post('pacientes', [App\Http\Controllers\API\PacienteController::class, 'store']);

    Route::get('/pacientes', [App\Http\Controllers\API\PacienteController::class, 'index']);

    Route::get('/pacientes/{id}', [App\Http\Controllers\API\PacienteController::class, 'show']);

    Route::put('/pacientes/{id}', [App\Http\Controllers\API\PacienteController::class, 'update']);

    Route::delete('/pacientes/{id}', [App\Http\Controllers\API\PacienteController::class, 'destroy']);

    // Import route for patients
    Route::post('/pacientes/importar',
    [App\Http\Controllers\API\PacienteController::class, 'importarPlanilha']);

    //utiilzando a view
    Route::get('importar-pacientes',
    [App\Http\Controllers\API\PacienteController::class, 'importarPacientes']);



    Route::get('/pacientes/search', [App\Http\Controllers\API\PacienteController::class, 'search']);

});

