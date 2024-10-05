<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutomatorTaskResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="AutomatorTaskResource",
     *     type="object",
     *     title="Automator Task Resource",
     *     description="Automator task details",
     * 
     *     @OA\Property(property="id", type="integer", example=1, description="ID of the task"),
     *     @OA\Property(property="processflow_step", type="string", example="Approval Step", description="The step of the processflow"),
     *     @OA\Property(property="processflow", type="string", example="Approval Process", description="The associated processflow"),
     *     @OA\Property(property="task_status", type="string", example="Pending", description="The current status of the task"),
     *     @OA\Property(property="assignment_status", type="string", example="Unassigned", description="The assignment status of the task"),
     *     @OA\Property(property="processflow_history", type="integer", example=15, description="The history ID of the processflow"),
     *     @OA\Property(property="entity", type="string", example="Finance", description="The entity associated with the task"),
     *     @OA\Property(property="entity_site_id", type="integer", example=12, description="The site ID of the entity")
     * )
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
