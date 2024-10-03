<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutomatorTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "processflow_step" => $this->processflowStep,
            "processflow" => $this->processflow,
            "task_status" => $this->task_status,
            "assignment_status" => $this->assignment_status,
            "processflow_history" => $this->processflow_history_id,
            "entity" => $this->entity,
            "entity_site_id" => $this->entity_site_id,
        ];
    }
}
