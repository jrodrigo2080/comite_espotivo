<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClubeController;
use App\Http\Controllers\RecursoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/clubes', [ClubeController::class, 'index']);
Route::post('/inserir-clube', [ClubeController::class, 'create']);
Route::post('/consumir-recurso', [ClubeController::class, 'consumirRecurso']);

Route::get('/recursos', [RecursoController::class, 'index']);
Route::post('/inserir-recurso', [RecursoController::class, 'create']);
