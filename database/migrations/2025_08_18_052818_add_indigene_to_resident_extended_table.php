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
        Schema::table('resident_extended', function (Blueprint $table) {
            $table->boolean('indigene')->default(0)->after('volunteer_activities')
                ->comment('1 if resident is indigenous to the community, 0 otherwise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resident_extended', function (Blueprint $table) {
            $table->dropColumn('indigene');
        });
    }
};
