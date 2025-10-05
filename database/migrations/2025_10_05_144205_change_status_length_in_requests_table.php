<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Increase the status column length so longer status strings are allowed.
            $table->string('status', 100)->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Revert to a smaller length if you must — pick the original value here.
            $table->string('status', 20)->default('pending')->change();
        });
    }
};