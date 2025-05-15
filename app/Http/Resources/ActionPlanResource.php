<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionPlanResource extends JsonResource
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
            'audit_id' => $this->audit_id,
            'action_item' => $this->action_item,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'responsible_user_id' => $this->responsible_user_id,
            'progress' => $this->progress,
            'completion_evidence' => $this->completion_evidence,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'audit' => $this->whenLoaded('audit'),
            'responsible_user' => $this->whenLoaded('responsibleUser'),
            'creator' => $this->whenLoaded('creator'),
        ];
    }
} 