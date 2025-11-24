<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add unique index to suppliers.contact. If you already have duplicate contacts
        // this migration will fail — remove or consolidate duplicates before running.
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unique('contact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['contact']);
        });
    }
};
