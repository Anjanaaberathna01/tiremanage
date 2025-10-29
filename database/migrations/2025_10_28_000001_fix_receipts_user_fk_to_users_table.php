<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            // Drop wrong FK (receipts.user_id -> requests.id)
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
                // FK might not exist on some environments; ignore
            }

            // Ensure column is correct type (unsigned big int) - usually is from foreignId
            // Recreate proper FK to users.id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            // Revert to the incorrect (original) FK only if needed
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {}
            $table->foreign('user_id')->references('id')->on('requests')->onDelete('cascade');
        });
    }
};
