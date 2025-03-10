<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Update this logic as per your requirements
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Define your validation rules here
        ];
    }

    /**
     * Pre-validate input modifications before validation rules are applied.
     *
     * @return void
     */
    protected function preValidation(): void
    {
        $this->merge([
            // Modify or sanitize request input here
            // e.g., 'field' => trim($this->input('field'))
        ]);
    }

    /**
     * After the validation rules have been applied, perform additional processing.
     *
     * @return void
     */
    protected function postValidation(): void
    {
        // Additional processing after validation
        // e.g., set defaults, enrich validated data, etc.
    }

    /**
     * Override the prepareForValidation method to call preValidation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->preValidation();
    }

    /**
     * Override the passedValidation method to call postValidation.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        $this->postValidation();
    }
}
