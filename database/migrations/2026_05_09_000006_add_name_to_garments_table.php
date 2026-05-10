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
        Schema::table('garments', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
        });

        DB::table('garments')
            ->whereNull('name')
            ->update(['name' => DB::raw('title')]);

        Schema::table('garments', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->dropColumn('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garments', function (Blueprint $table) {
            $table->string('title')->nullable()->after('user_id');
        });

        DB::table('garments')
            ->whereNull('title')
            ->update(['title' => DB::raw('name')]);

        Schema::table('garments', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->dropColumn('name');
        });
    }
};
