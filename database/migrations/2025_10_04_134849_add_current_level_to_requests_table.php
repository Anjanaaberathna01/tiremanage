<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedTinyInteger('current_level')->default(1)->after('status'); // 1 = Section Manager, 2 = Mechanic, 0 = Finished
        });
    }

    public function down() {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('current_level');
        });
    }
};