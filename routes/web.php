<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\InformeController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

Route::prefix('reportes')->group(function () {
        // Vista principal de reportes
        Route::get('/', function () {
            return view('reportes.index');
        })->name('reportes');

        // Formulario de reportes
        Route::get('/formulario', function () {
            return view('reportes.formulario');
        })->name('reportes.formulario');  // Nombre actualizado

        // Informe mensual
        Route::get('/mensual', [InformeController::class, 'generarInforme'])
            ->name('reportes.mensual');    // Nombre actualizado
    });
});

// Rutas de Login
Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest')
                ->name('login');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest');

// Ruta de Logout
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

// Rutas de Registro
Route::get('register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');

Route::post('register', [RegisteredUserController::class, 'store'])
                ->middleware('guest');


Route::get('/informe-formulario', function () {
    return view('informes.formulario');
})->name('informe-formulario');

Route::get('/informe-mensual', [InformeController::class, 'generarInforme'])->name('informe-mensual');
                
Route::get('/run-node-server', function () {
    try {
        // Ejecutar el comando de Node.js
        $output = shell_exec('node /ruta/al/server3.js');  // Asegúrate de que la ruta sea correcta
        return response()->json(['status' => 'success', 'output' => $output]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
})->middleware('auth');

require __DIR__.'/auth.php';