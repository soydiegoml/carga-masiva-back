<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\PersonaController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Ruta pública para login (sin middleware auth:api)
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token de autenticación)
Route::middleware('auth:api')->get('/persona/{page}', [PersonaController::class, 'obtenerPersonas']);
Route::middleware('auth:api')->post('/upload', [UploadController::class, 'upload']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

