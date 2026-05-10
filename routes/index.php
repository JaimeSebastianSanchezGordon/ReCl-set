<?php

use App\Http\Controllers\GarmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — ReClóset
|--------------------------------------------------------------------------
|
| Las URLs públicas usan términos en español.
| Los nombres internos de ruta (name) son semánticos en español
| con prefijo "prendas." para claridad del equipo.
|
| El modelo se mantiene como Garment (inglés) según el Spec Kit.
|
*/

// ── Página principal → redirige a Explorar ──
Route::get('/', [GarmentController::class, 'index'])->name('home');

// ── Explorar — galería pública de prendas disponibles ──
Route::get('/explorar', [GarmentController::class, 'index'])->name('garments.explore');

// ── Mis Prendas — panel privado del usuario ──
Route::get('/mis-prendas', [GarmentController::class, 'myGarments'])->name('garments.my');

// ── CRUD de prendas con URLs en español ──
Route::get('/prendas/crear', [GarmentController::class, 'create'])->name('garments.create');
Route::post('/prendas', [GarmentController::class, 'store'])->name('garments.store');
Route::get('/prendas/{garment}', [GarmentController::class, 'show'])->name('garments.show');
Route::get('/prendas/{garment}/editar', [GarmentController::class, 'edit'])->name('garments.edit');
Route::put('/prendas/{garment}', [GarmentController::class, 'update'])->name('garments.update');
Route::delete('/prendas/{garment}', [GarmentController::class, 'destroy'])->name('garments.destroy');

// ── Cambio de estado (ruta dedicada, solo propietario) ──
Route::patch('/prendas/{garment}/estado', [GarmentController::class, 'updateStatus'])->name('garments.updateStatus');
