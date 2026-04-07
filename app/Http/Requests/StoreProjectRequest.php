<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-_.,()]+$/'],
            'description' => ['required', 'string', 'max:5000'],
            'street_id' => ['required', 'exists:streets,id'],
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
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
            'title.required' => 'Project title is required.',
            'title.max' => 'Project title must not exceed 255 characters.',
            'title.regex' => 'Project title contains invalid characters.',
            'description.required' => 'Project description is required.',
            'description.max' => 'Project description must not exceed 5000 characters.',
            'street_id.required' => 'Please select a street for this project.',
            'street_id.exists' => 'The selected street does not exist.',
            'status.required' => 'Project status is required.',
            'status.in' => 'Invalid project status selected.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Please provide a valid start date.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'Please provide a valid end date.',
            'end_date.after' => 'End date must be after the start date.',
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
            'street_id' => 'street',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'actual_cost' => 'actual cost',
        ];
    }
}
