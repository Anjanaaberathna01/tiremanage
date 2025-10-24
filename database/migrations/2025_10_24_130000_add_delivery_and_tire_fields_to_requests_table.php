<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('delivery_place_office')->nullable()->after('damage_description');
            $table->string('delivery_place_street')->nullable()->after('delivery_place_office');
            $table->string('delivery_place_town')->nullable()->after('delivery_place_street');
            $table->date('last_tire_replacement_date')->nullable()->after('delivery_place_town');
            $table->string('existing_tire_make')->nullable()->after('last_tire_replacement_date');
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_place_office',
                'delivery_place_street',
                'delivery_place_town',
                'last_tire_replacement_date',
                'existing_tire_make',
            ]);
        });
    }
};
