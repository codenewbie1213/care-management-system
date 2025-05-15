<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['Admin', 'Manager']);
    }

    public function rules(): array
    {
        return [
            'audit_template_id' => ['required', 'exists:audit_templates,id'],
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'auditor_ids' => ['required', 'array', 'min:1'],
            'auditor_ids.*' => ['exists:users,id'],
        ];
    }
} 