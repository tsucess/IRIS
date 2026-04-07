<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResidentExtendedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'number_of_children' => ['nullable', 'integer', 'min:0', 'max:50'],
            'ethnicity' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'education_level' => ['nullable', 'string', 'max:255'],
            'employment_status' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'income_bracket' => ['nullable', 'string', 'max:255'],
            'has_disability' => ['nullable', 'boolean'],
            'disability_type' => ['nullable', 'string', 'max:255'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'health_conditions' => ['nullable', 'string', 'max:1000'],
            'is_voter' => ['nullable', 'boolean'],
            'is_taxpayer' => ['nullable', 'boolean'],
            'date_of_death' => ['nullable', 'date', 'after:date_of_birth'],
            'household_size' => ['nullable', 'integer', 'min:1', 'max:100'],
            'access_to_electricity' => ['nullable', 'boolean'],
            'access_to_clean_water' => ['nullable', 'boolean'],
            'access_to_sanitation' => ['nullable', 'boolean'],
            'internet_access' => ['nullable', 'boolean'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:255'],
            'civic_participation' => ['nullable', 'boolean'],
            'volunteer_activities' => ['nullable', 'boolean'],
            'indigene' => ['nullable', 'boolean'],
            'country' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'gender.in' => 'Please select a valid gender option.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'marital_status.in' => 'Please select a valid marital status.',
            'number_of_children.min' => 'Number of children cannot be negative.',
            'number_of_children.max' => 'Number of children seems unrealistic.',
            'phone_number.regex' => 'Please enter a valid phone number.',
            'email.email' => 'Please provide a valid email address.',
            'household_size.min' => 'Household size must be at least 1.',
            'household_size.max' => 'Household size seems unrealistic.',
            'emergency_contact_phone.regex' => 'Please enter a valid emergency contact phone number.',
            'date_of_death.after' => 'Date of death must be after date of birth.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'middle_name' => 'middle name',
            'date_of_birth' => 'date of birth',
            'place_of_birth' => 'place of birth',
            'marital_status' => 'marital status',
            'number_of_children' => 'number of children',
            'postal_code' => 'postal code',
            'phone_number' => 'phone number',
            'education_level' => 'education level',
            'employment_status' => 'employment status',
            'income_bracket' => 'income bracket',
            'has_disability' => 'disability status',
            'disability_type' => 'disability type',
            'blood_group' => 'blood group',
            'health_conditions' => 'health conditions',
            'is_voter' => 'voter status',
            'is_taxpayer' => 'taxpayer status',
            'date_of_death' => 'date of death',
            'household_size' => 'household size',
            'access_to_electricity' => 'electricity access',
            'access_to_clean_water' => 'clean water access',
            'access_to_sanitation' => 'sanitation access',
            'internet_access' => 'internet access',
            'emergency_contact_name' => 'emergency contact name',
            'emergency_contact_phone' => 'emergency contact phone',
            'emergency_contact_relation' => 'emergency contact relation',
            'civic_participation' => 'civic participation',
            'volunteer_activities' => 'volunteer activities',
        ];
    }
}
