<?php

namespace App\Http\Requests\Leave;

class ReviewLeaveRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * The `role:admin` route middleware already blocks non-admins from
     * reaching this route; this authorize() call is a defense-in-depth
     * check so the request is rejected even if the middleware were
     * ever removed or misconfigured on a future route.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'admin_remarks' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('admin_remarks')) {
            $this->merge([
                'admin_remarks' => $this->filled('admin_remarks')
                    ? trim(strip_tags((string) $this->input('admin_remarks')))
                    : null,
            ]);
        }
    }
}
