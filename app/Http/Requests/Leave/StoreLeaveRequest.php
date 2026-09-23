<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    /**
     * Only an authenticated staff member may submit a leave request
     * for themselves. Combined with the `role:staff` route middleware,
     * this is a second, request-level layer of the RBAC check.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'leave_type' => ['required', 'string', 'in:annual,mc,emergency,unpaid'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            // Free-text field: length-capped and later stripped of any
            // HTML/script tags in prepareForValidation() to prevent
            // stored XSS, on top of Blade's automatic {{ }} escaping.
            'reason'     => ['required', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'End date cannot be earlier than the start date.',
            'start_date.after_or_equal' => 'You cannot backdate a leave application.',
        ];
    }

    /**
     * Sanitize free-text input before validation runs. strip_tags()
     * removes any HTML/JS payload a malicious user might submit
     * (defense in depth — Blade's {{ }} output escaping is the
     * primary XSS guard when the value is later displayed).
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('reason')) {
            $this->merge([
                'reason' => trim(strip_tags((string) $this->input('reason'))),
            ]);
        }
    }
}
