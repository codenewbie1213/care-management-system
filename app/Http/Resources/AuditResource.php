<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
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
            'audit_template_id' => $this->audit_template_id,
            'name' => $this->name,
            'department' => $this->department,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'completed_at' => $this->completed_at,
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'template' => new AuditTemplateResource($this->whenLoaded('template')),
            'auditors' => $this->whenLoaded('auditors'),
            'responses' => AuditResponseResource::collection($this->whenLoaded('responses')),
        ];
    }
} 