<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GarmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ReCloset
|--------------------------------------------------------------------------
|
| Las URLs publicas usan terminos en espanol.
| El modelo se mantiene como Garment (ingles) segun el Spec Kit.
|
*/

Route::get('/', [GarmentController::class, 'index'])->name('home');
Route::get('/explorar', [GarmentController::class, 'index'])->name('garments.explore');

Route::middleware('guest')->group(function () {
    Route::get('/registrarse', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registrarse', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/olvide-contrasena', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/olvide-contrasena', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/restablecer-contrasena/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/restablecer-contrasena', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/mis-prendas', [GarmentController::class, 'myGarments'])->name('garments.my');
    Route::get('/prendas/crear', [GarmentController::class, 'create'])->name('garments.create');
    Route::post('/prendas', [GarmentController::class, 'store'])->name('garments.store');
    Route::get('/prendas/{garment}/editar', [GarmentController::class, 'edit'])->name('garments.edit');
    Route::put('/prendas/{garment}', [GarmentController::class, 'update'])->name('garments.update');
    Route::delete('/prendas/{garment}', [GarmentController::class, 'destroy'])->name('garments.destroy');
    Route::patch('/prendas/{garment}/estado', [GarmentController::class, 'updateStatus'])->name('garments.updateStatus');
});

Route::get('/prendas/{garment}', [GarmentController::class, 'show'])->name('garments.show');
