<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'audit_id' => ['nullable', 'exists:audits,id'],
            'action_item' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:open,in_progress,completed,overdue'],
            'due_date' => ['required', 'date'],
            'responsible_user_id' => ['required', 'exists:users,id'],
            'progress' => ['nullable', 'string'],
            'completion_evidence' => ['nullable', 'file', 'max:10240'],
        ];
    }
} 