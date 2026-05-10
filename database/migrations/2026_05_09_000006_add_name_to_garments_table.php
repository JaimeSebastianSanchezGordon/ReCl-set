<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Esta migración ya no es necesaria porque la tabla garments
        // se crea con la columna `name` desde el principio.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay acción necesaria en el rollback de esta migración.
    }
};
