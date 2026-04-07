<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentExtendedTables extends Migration
{
    public function up(): void
    {
        Schema::create('resident_extended', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Personal details
            $table->string('middle_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->integer('number_of_children')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('religion')->nullable();

            // Contact & Address
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();

            // Education & Employment
            $table->enum('education_level', ['none', 'primary', 'secondary', 'tertiary', 'vocational'])->nullable();
            $table->enum('employment_status', ['employed', 'unemployed', 'self-employed', 'retired'])->nullable();
            $table->string('occupation')->nullable();
            $table->enum('income_bracket', ['low', 'middle', 'high'])->nullable();

            // Health & Special Needs
            $table->boolean('has_disability')->default(false);
            $table->string('disability_type')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('health_conditions')->nullable();

            // Civic Info
            $table->boolean('is_voter')->default(false);
            $table->boolean('is_taxpayer')->default(false);
            $table->date('date_of_death')->nullable();

            // Household Info
            $table->integer('household_size')->nullable();
            $table->boolean('access_to_electricity')->default(false);
            $table->boolean('access_to_clean_water')->default(false);
            $table->boolean('access_to_sanitation')->default(false);
            $table->boolean('internet_access')->default(false);

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();

            // Community Participation
            $table->boolean('civic_participation')->default(false);
            $table->boolean('volunteer_activities')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resident_extended');
    }
}
