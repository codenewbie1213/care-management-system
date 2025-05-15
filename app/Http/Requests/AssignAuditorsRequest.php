<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignAuditorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['Admin', 'Manager']);
    }

    public function rules(): array
    {
        return [
            'auditor_ids' => ['required', 'array', 'min:1'],
            'auditor_ids.*' => ['exists:users,id'],
        ];
    }
} 