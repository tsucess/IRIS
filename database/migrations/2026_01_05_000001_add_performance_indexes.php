<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration adds critical indexes to improve query performance
     * across the application, especially for dashboard and search operations.
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    public function up(): void
    {
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            if (!$this->hasIndex('users', 'idx_users_street_id'))  $table->index('street_id',  'idx_users_street_id');
            if (!$this->hasIndex('users', 'idx_users_role'))        $table->index('role',        'idx_users_role');
            if (!$this->hasIndex('users', 'idx_users_id_number'))   $table->index('id_number',   'idx_users_id_number');
            if (!$this->hasIndex('users', 'idx_users_email'))       $table->index('email',       'idx_users_email');
            if (!$this->hasIndex('users', 'idx_users_created_at'))  $table->index('created_at',  'idx_users_created_at');
        });

        // Resident Extended table indexes
        Schema::table('resident_extended', function (Blueprint $table) {
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_user_id'))       $table->index('user_id',           'idx_resident_ext_user_id');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_gender'))        $table->index('gender',            'idx_resident_ext_gender');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_marital_status'))$table->index('marital_status',    'idx_resident_ext_marital_status');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_ethnicity'))     $table->index('ethnicity',         'idx_resident_ext_ethnicity');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_religion'))      $table->index('religion',          'idx_resident_ext_religion');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_education'))     $table->index('education_level',   'idx_resident_ext_education');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_employment'))    $table->index('employment_status', 'idx_resident_ext_employment');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_occupation'))    $table->index('occupation',        'idx_resident_ext_occupation');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_income'))        $table->index('income_bracket',    'idx_resident_ext_income');
            if (!$this->hasIndex('resident_extended', 'idx_resident_ext_indigene'))      $table->index('indigene',          'idx_resident_ext_indigene');
        });

        // Streets table indexes
        Schema::table('streets', function (Blueprint $table) {
            if (!$this->hasIndex('streets', 'idx_streets_zone')) $table->index('zone', 'idx_streets_zone');
            if (!$this->hasIndex('streets', 'idx_streets_name')) $table->index('name', 'idx_streets_name');
        });

        // Projects table indexes
        Schema::table('projects', function (Blueprint $table) {
            if (!$this->hasIndex('projects', 'idx_projects_street_id'))  $table->index('street_id',  'idx_projects_street_id');
            if (!$this->hasIndex('projects', 'idx_projects_status'))     $table->index('status',     'idx_projects_status');
            if (!$this->hasIndex('projects', 'idx_projects_start_date')) $table->index('start_date', 'idx_projects_start_date');
            if (!$this->hasIndex('projects', 'idx_projects_end_date'))   $table->index('end_date',   'idx_projects_end_date');
            if (!$this->hasIndex('projects', 'idx_projects_created_at')) $table->index('created_at', 'idx_projects_created_at');
        });

        // Tasks table indexes
        Schema::table('tasks', function (Blueprint $table) {
            if (!$this->hasIndex('tasks', 'idx_tasks_project_id'))  $table->index('project_id',  'idx_tasks_project_id');
            if (!$this->hasIndex('tasks', 'idx_tasks_assigned_to')) $table->index('assigned_to', 'idx_tasks_assigned_to');
            if (!$this->hasIndex('tasks', 'idx_tasks_status'))      $table->index('status',      'idx_tasks_status');
            if (!$this->hasIndex('tasks', 'idx_tasks_due_date'))    $table->index('due_date',    'idx_tasks_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_street_id');
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_id_number');
            $table->dropIndex('idx_users_email');
            $table->dropIndex('idx_users_created_at');
        });

        // Resident Extended table
        Schema::table('resident_extended', function (Blueprint $table) {
            $table->dropIndex('idx_resident_ext_user_id');
            $table->dropIndex('idx_resident_ext_gender');
            $table->dropIndex('idx_resident_ext_marital_status');
            $table->dropIndex('idx_resident_ext_ethnicity');
            $table->dropIndex('idx_resident_ext_religion');
            $table->dropIndex('idx_resident_ext_education');
            $table->dropIndex('idx_resident_ext_employment');
            $table->dropIndex('idx_resident_ext_occupation');
            $table->dropIndex('idx_resident_ext_income');
            $table->dropIndex('idx_resident_ext_indigene');
        });

        // Streets table
        Schema::table('streets', function (Blueprint $table) {
            $table->dropIndex('idx_streets_zone');
            $table->dropIndex('idx_streets_name');
        });

        // Projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('idx_projects_street_id');
            $table->dropIndex('idx_projects_status');
            $table->dropIndex('idx_projects_start_date');
            $table->dropIndex('idx_projects_end_date');
            $table->dropIndex('idx_projects_created_at');
        });

        // Tasks table
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('idx_tasks_project_id');
            $table->dropIndex('idx_tasks_assigned_to');
            $table->dropIndex('idx_tasks_status');
            $table->dropIndex('idx_tasks_due_date');
        });
    }
};
