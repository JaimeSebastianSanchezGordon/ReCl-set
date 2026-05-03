<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // These tables are intentionally removed because the application
        // no longer relies on database-backed cache or queue storage.
    }
};
