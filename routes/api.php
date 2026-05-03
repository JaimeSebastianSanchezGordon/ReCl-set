<?php

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
