<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentExtended extends Model
{
    use HasFactory;

    protected $table = 'resident_extended';

    protected $fillable = [
        'user_id',
        'middle_name',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'marital_status',
        'number_of_children',
        'ethnicity',
        'religion',
        'address',
        'city',
        'state',
        'postal_code',
        'phone_number',
        'email',
        'education_level',
        'employment_status',
        'occupation',
        'income_bracket',
        'has_disability',
        'blood_group',
        'is_voter',
        'is_taxpayer',
        'household_size',
        'access_to_electricity',
        'access_to_clean_water',
        'access_to_sanitation',
        'internet_access',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'civic_participation',
        'volunteer_activities',
        'indigene',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
