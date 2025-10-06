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
Schema::create('receipts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('request_id')->constrained()->onDelete('cascade');
    $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
    $table->text('description')->nullable();
    $table->decimal('amount', 10, 2)->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
