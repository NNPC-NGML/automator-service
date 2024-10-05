<?php

namespace App\Http\Controllers;

use App\Models\ProcessFlow;
use Illuminate\Http\Request;
use App\Models\AutomatorTask;
use App\Models\ProcessFlowStep;
use App\Http\Resources\UserResource;
use App\Service\AutomatorTaskService;
use Skillz\Nnpcreusable\Models\HeadOfUnit;
use App\Http\Resources\AutomatorTaskResource;
use App\Jobs\AutomatorTask\AutomatorTaskCreated;

class AutomatorTaskController extends Controller
{

    private AutomatorTaskService $automatorTaskService;

    public function __construct(AutomatorTaskService $automatorTaskService)
    {
        $this->automatorTaskService = $automatorTaskService;
    }

    /**
     * @OA\Get(
     *     path="/api/unassignedtasks",
     *     tags={"Automator Tasks"},
     *     summary="Get head of unit's assignable tasks",
     *     description="Retrieves tasks that are assigned to the head of the unit and can be further assigned to other users.",
     *     operationId="getHeadOfUnitAssignableTask",
     *     security={{"bearerAuth": {}}},
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Successful task retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AutomatorTaskResource")
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="No tasks found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No tasks found")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */

    public function getHeadOfUnitAssignableTask()
    {
        $userId = auth()->id();
        $task = $this->automatorTaskService->getTaskWithUserId($userId);
        return AutomatorTaskResource::collection($task)->additional(['status' => 'success']);
    }

    /**
     * @OA\Get(
     *     path="/api/task-assignable-users/{id}",
     *     tags={"Automator Tasks"},
     *     summary="Get assignable users for a task",
     *     description="Retrieve users who can be assigned to a specific task based on process flow and user designation/unit.",
     *     operationId="getTaskAssignableUsers",
     *     security={{"bearerAuth": {}}},
     * 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="List of assignable users",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task not found")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */

    public function getTaskAssignableUsers($id)
    {
        $userId = auth()->id();
        $task = $this->automatorTaskService->getTask($id);

        if ($task) {
            $user = HeadOfUnit::where('user_id', $task->user_id)->first();
            $getprocessflow = ProcessFlow::where('id', $task->processflow_id)->first();
            //check if the task processflow id is the start processflow id
            if ($task->processflow_step_id == $getprocessflow->start_step_id) {
                //get process flow step u
                $processFlowDetails = [
                    'designation' => $getprocessflow->start_user_designation,
                    'unit' => $getprocessflow->start_user_unit,
                ];
            } else {

                $getPreviousprocessFlowStep = ProcessFlowStep::where('id', $task->processflow_step_id)->with(['previousStep'])->first();
                $processFlowDetails = [
                    'designation' => $getPreviousprocessFlowStep->previousStep->next_user_designation,
                    'unit' => $getPreviousprocessFlowStep->previousStep->next_user_unit,
                ];
            }
            //get user
            $users = $this->automatorTaskService->getUserWithUnitAndDesignation($processFlowDetails['unit'], $processFlowDetails['designation'], $userId);
            return UserResource::collection($users)->additional(['status' => 'success']);
        }
        return response()->json(['message' => 'Task not found'], 404);
    }

    /**
     * @OA\Post(
     *     path="/api/assign-task-to-user",
     *     tags={"Automator Tasks"},
     *     summary="Assign a task to a head of unit's subordinate",
     *     description="This endpoint assigns a task to a user's subordinate and dispatches an event if the task is successfully assigned.",
     *     operationId="assignTaskToHeadOfUnitSubordinate",
     *     security={{"bearerAuth": {}}},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"task_id", "user_id"},
     *             @OA\Property(property="task_id", type="integer", example=1, description="ID of the task to be assigned"),
     *             @OA\Property(property="user_id", type="integer", example=2, description="ID of the user to whom the task is assigned")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Task assigned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Task assigned successfully")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Task not found")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function assignTaskToHeadOfUnitSubordinate(Request $request)
    {
        // validate that user id and task id is available
        $assignedBy = auth()->id();
        $task = $this->automatorTaskService->assignTaskToUser($request->task_id, $request->user_id, $assignedBy);
        if ($task) {
            $newTask = AutomatorTask::where(["id" => $request->task_id])
                ->with([
                    "processflowHistory",
                    "processflow",
                    "processflowStep.route",
                ])->first();
            $convertedRoute = $this->automatorTaskService->routeConverter($newTask);

            $data = $newTask->toArray();
            $data["route"] = $convertedRoute;
            // get all task relationship, structure the route convert to array add new keys to array like route url and then push to other services 
            foreach (config("nnpcreusable.AUTOMATOR_TASK_CREATED") as $queue) {
                AutomatorTaskCreated::dispatch($data)->onQueue($queue);
            }
            return response()->json(['status' => "success", 'message' => 'Task assigned successfully'], 200);
        }
        return response()->json(['status' => "error", 'message' => 'Task not found'], 404);
    }
}
