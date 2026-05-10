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
        // Esta columna ya existe en la migración de creación de la tabla garments.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No se requiere ninguna acción en el rollback.
    }
};
