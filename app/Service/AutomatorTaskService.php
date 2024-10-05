<?php

namespace App\Service;

use App\Models\User;
use App\Models\AutomatorTask;
use Illuminate\Support\Facades\Validator;
use Skillz\Nnpcreusable\Models\HeadOfUnit;
use Skillz\Nnpcreusable\Models\CustomerSite;
use Illuminate\Validation\ValidationException;
use Skillz\Nnpcreusable\Service\HeadOfUnitService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AutomatorTaskService
{
    /**
     * Create a new task.
     *
     * @param Array $data  holds valid data for the new task.
     *
     * @return \App\Models\AutomatorTask \ Illuminate\Support\MessageBag The created task model & MessageBag when there is an error.
     */
    public function createTask($data)
    {
        $createTask = AutomatorTask::create($data);
        return $createTask;
    }

    /**
     * Update automator task.
     *
     * @param Array $data  holds valid data for the new task.
     *
     * @return bool
     */
    public function updateTask($id, $data)
    {
        $getTask = AutomatorTask::findOrFail($id);
        if ($getTask) {
            $updateTask = $getTask->update($data);
            return $updateTask;
        }
        return false;
    }
    /**
     * Update automator task.
     *
     * @param Array $data  holds valid data for the new task.
     *
     * @return \App\Models\AutomatorTask \ Illuminate\Support\MessageBag The updated task model & MessageBag when there is an error.
     */
    public function getTask($id)
    {
        $getTask = AutomatorTask::findOrFail($id);
        if ($getTask) {
            return $getTask;
        }
        return false;
    }

    public function newTaskFromPreviousTask($data)
    {
        //get automator task with relationship
        $automatorTask = AutomatorTask::find($data["id"])->with(["processflowHistory", "processflow", "processflowStep"])->first();
        if ($automatorTask) {
            if ($automatorTask->processflowStep->next_step_id > 0) {
                $newData = [];
                if ($automatorTask->processflowStep->next_user_designation < 1) {
                    // use previous user
                    $newData["user_id"] = $automatorTask->user_id;
                    $newData["assignment_status"] = AutomatorTask::ASSIGNED;
                } else {
                    //use $automatorTask->entity_site_id to get the customer zone
                    // dynamicaly search for a user based on the next step data for user 

                    $getCustomerZone = $this->getCustomerZone($automatorTask->entity_site_id);
                    $dynamicUserData = [
                        "location" => $getCustomerZone,
                        //"department" => $automatorTask->processflowStep->next_user_department,
                        "unit" => $automatorTask->processflowStep->next_user_unit,
                        //"designation" => $automatorTask->processflowStep->next_user_designation,
                    ];

                    $newData["user_id"] = $this->getHeadOfUnit($dynamicUserData);
                    $newData["assignment_status"] = AutomatorTask::UNASSIGNED;
                }
                if ($automatorTask->entity_id > 0) {
                    $newData["entity_id"] = $automatorTask->entity_id;
                }

                if (!empty($automatorTask->entity)) {
                    $newData["entity"] = $automatorTask->entity;
                }

                if ($automatorTask->entity_site_id > 0) {
                    $newData["entity_site_id"] = $automatorTask->entity_site_id;
                }
                $newData["processflow_id"] = $automatorTask->processflow_id;
                $newData["processflow_step_id"] = $automatorTask->processflowStep->next_step_id;
                $newData["task_status"] = AutomatorTask::PENDING;
                return $this->createTask($newData);
            }
        }
    }

    public function getHeadOfUnit($data): int
    {
        return (new HeadOfUnitService())->getHeadOfUnitByUnitAndLocaltion($data["unit"], $data["location"])->user_id;
    }
    private function getCustomerZone(int $data)
    {
        $model = CustomerSite::find($data);
        if ($model) {
            return $model->ngml_zone_id;
        }
        return 0;
    }

    public function getTaskWithUserId($id)
    {
        $getTask = AutomatorTask::where(["user_id" => $id, "assignment_status" => AutomatorTask::UNASSIGNED])->get();
        if ($getTask) {
            return $getTask;
        }
        return false;
    }
    public function getUserWithUnitAndDesignation($unitId, $designationId, $userId = 0)
    {
        $users = User::where('id', '!=', $userId)->whereHas('usersUnit', function ($query) use ($unitId) {
            $query->where('unit_id', $unitId);
        })->whereHas('userDesignation', function ($query) use ($designationId) {
            $query->where('designation_id', $designationId);
        })->get();
        return $users;
    }

    public function assignTaskToUser($id, $userId, $assignedBy)
    {
        $task = AutomatorTask::find($id);
        if (!$task) {
            return false;
        }
        $task->user_id = $userId;
        $task->assignedBy = $assignedBy;
        $task->assignment_status = AutomatorTask::ASSIGNED;
        $task->save();
        return $task;
    }

    public function routeConverter($data)
    {
        $routeData = $data->processflowStep->route;
        $route = $routeData->link;
        foreach (json_decode($routeData->dynamic_content)  as $dynamicRoute) {
            switch ($dynamicRoute) {
                case "customer_id":
                    $route = $route . "/" . $data->entity_id;
                    break;
                case "customer_site_id":
                    $route = $route . "/" . $data->entity_site_id;
                    break;
            }
        }

        return $route;
    }
}
