<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Ruta para acceder al backend
Route::get('/back', function () {
    // Reemplazar '/tasks' con la ruta real que apunta al inicio del backend dentro de la carpeta "back"
    return redirect('/tasks');
});

// Rutas del frontend (VueJS)
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Rutas del frontend

