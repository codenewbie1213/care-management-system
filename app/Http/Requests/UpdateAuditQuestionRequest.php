<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuditQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Admin');
    }

    public function rules(): array
    {
        return [
            'question_text' => ['sometimes', 'string', 'max:1000'],
            'type' => ['sometimes', 'string', 'in:multiple_choice,text,date'],
            'score' => ['sometimes', 'numeric', 'min:0'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string'],
            'required' => ['boolean'],
        ];
    }
} 