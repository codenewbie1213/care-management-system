<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'audit_id' => ['nullable', 'exists:audits,id'],
            'action_item' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', 'in:open,in_progress,completed,overdue'],
            'due_date' => ['sometimes', 'date'],
            'responsible_user_id' => ['sometimes', 'exists:users,id'],
            'progress' => ['nullable', 'string'],
            'completion_evidence' => ['nullable', 'file', 'max:10240'],
        ];
    }
} 