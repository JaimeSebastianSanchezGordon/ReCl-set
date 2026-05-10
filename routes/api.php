<?php

use App\Http\Controllers\GarmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'service' => config('app.name', 'Laravel'),
        'timestamp' => now()->toISOString(),
    ]);
});

Route::get('/me', function (Request $request) {
    return response()->json([
        'authenticated' => (bool) $request->user(),
        'user' => $request->user(),
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('my-garments', [GarmentController::class, 'myGarments'])->name('api.garments.my');
    Route::patch('garments/{garment}/status', [GarmentController::class, 'updateStatus'])->name('api.garments.updateStatus');
    Route::apiResource('garments', GarmentController::class)->names('api.garments');
});
