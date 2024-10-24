<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\InformeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Grupo de reportes
    Route::prefix('reportes')->group(function () {
        Route::get('/', function () {
            return view('reportes.index');
        })->name('reportes');

        // Formulario de reportes (vacío)
        Route::get('/formulario', function () {
            return view('reportes.formulario');
        })->name('reportes.formulario');

        // Informe mensual (con toda la funcionalidad)
        Route::get('/mensual', [InformeController::class, 'generarInforme'])
            ->name('reportes.mensual');
    });

    // Node server
    Route::get('/run-node-server', function () {
        try {
            $output = shell_exec('node /ruta/al/server3.js');
            return response()->json(['status' => 'success', 'output' => $output]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    });
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__.'/auth.php';