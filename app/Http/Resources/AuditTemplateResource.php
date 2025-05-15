<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'scoring_method' => $this->scoring_method,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'questions' => AuditQuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
} 