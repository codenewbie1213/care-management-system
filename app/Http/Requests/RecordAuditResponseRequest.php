<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordAuditResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only assigned auditors can submit responses
        $audit = $this->route('audit');
        return $audit && $this->user() && $audit->auditors->contains($this->user()->id);
    }

    public function rules(): array
    {
        return [
            'responses' => ['required', 'array', 'min:1'],
            'responses.*.audit_question_id' => ['required', 'exists:audit_questions,id'],
            'responses.*.response' => ['nullable', 'string'],
            'responses.*.score' => ['required', 'numeric', 'min:0'],
            'responses.*.notes' => ['nullable', 'string'],
        ];
    }
} 