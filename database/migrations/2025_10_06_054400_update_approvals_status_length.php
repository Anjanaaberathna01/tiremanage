<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    \Illuminate\Support\Facades\Schema::table('approvals', function ($table) {
        $table->string('status', 50)->change(); // make VARCHAR(50)
    });
}

public function down(): void
{
    \Illuminate\Support\Facades\Schema::table('approvals', function ($table) {
        $table->string('status', 50)->change(); // revert back
    });
}

};
