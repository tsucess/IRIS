<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('allocated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('resource_type', ['funds', 'materials', 'manpower', 'equipment', 'other'])
                ->default('funds');
            $table->string('name');
            $table->string('unit')->nullable();
            $table->decimal('allocated_amount', 14, 2)->default(0);
            $table->decimal('used_amount', 14, 2)->default(0);
            $table->enum('status', ['planned', 'approved', 'in_use', 'depleted', 'cancelled'])
                ->default('planned');
            $table->date('allocated_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['project_id', 'status']);
            $table->index('resource_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_allocations');
    }
};
