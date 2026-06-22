<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resident_extended', function (Blueprint $table) {
            if (! Schema::hasColumn('resident_extended', 'occupation_id')) {
                $table->foreignId('occupation_id')
                    ->nullable()
                    ->after('occupation')
                    ->constrained('occupations')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('resident_extended', function (Blueprint $table) {
            if (Schema::hasColumn('resident_extended', 'occupation_id')) {
                $table->dropConstrainedForeignId('occupation_id');
            }
        });
    }
};
