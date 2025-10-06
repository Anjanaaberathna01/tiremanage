<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('receipts', function (Blueprint $table) {
        $table->foreignId('request_id')->nullable()->constrained('requests')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('receipts', function (Blueprint $table) {
        $table->dropForeign(['request_id']);
        $table->dropColumn('request_id');
    });
}
};
