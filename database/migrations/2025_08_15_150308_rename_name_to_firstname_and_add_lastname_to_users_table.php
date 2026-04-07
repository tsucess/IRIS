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
        Schema::table('users', function (Blueprint $table) {
            // Rename 'name' to 'firstname'
            $table->renameColumn('name', 'firstname');

            // Add 'lastname'
            $table->string('lastname')->after('firstname')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse changes
            $table->renameColumn('firstname', 'name');
            $table->dropColumn('lastname');
        });

    }
};
