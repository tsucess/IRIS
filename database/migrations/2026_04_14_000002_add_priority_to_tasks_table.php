<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tasks', 'priority')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])
                      ->default('medium')
                      ->after('status');
            });
        } else {
            // Column exists (from original create_tasks_table) but may be missing 'urgent' — upgrade it.
            // MySQL/MariaDB supports ENUM modification via raw ALTER. SQLite stores enums as TEXT with a
            // CHECK constraint and doesn't support MODIFY COLUMN, so we skip the upgrade in that case.
            $driver = DB::connection()->getDriverName();
            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                DB::statement("ALTER TABLE tasks MODIFY COLUMN priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium'");
            }
        }
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
};
