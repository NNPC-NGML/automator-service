<?php

namespace App\Service;

use App\Models\AutomatorTask;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
     * @return \App\Models\AutomatorTask \ Illuminate\Support\MessageBag The updated task model & MessageBag when there is an error.
     */
    public function updateTask($data)
    {
        $getTask = AutomatorTask::findOrFail($data["id"]);
        if ($getTask) {
            $updateTask = $getTask->update($data);
            return $updateTask;
        }
        return false;
    }

    public function newTaskFromPreviousTask($data)
    {
        //get automator task with relationship
        $automatorTask = AutomatorTask::find($data["id"])->with(["processflowHistory", "processflow", "processflowStep"])->first();
        if ($automatorTask->processflowStep->next_step_id > 0) {
            $newData = [];
            if ($automatorTask->processflowStep->next_user_designation < 1) {
                // use previous user
                $newData["user_id"] = $automatorTask->user_id;
            } else {
                // dynamicaly search for a user based on the next step data for user 
            }
            if ($automatorTask->entity_id > 0) {
                $newData["entity_id"] = $automatorTask->entity_id;
            }

            if ($automatorTask->entity_site_id > 0) {
                $newData["entity_site_id"] = $automatorTask->entity_site_id;
            }
            $newData["processflow_id"] = $automatorTask->processflow_id;
            $newData["processflow_step_id"] = $automatorTask->processflowStep->next_step_id;
            $newData["task_status"] = AutomatorTask::PENDING;
            return $this->createTask($newData);
        }
        return $automatorTask;
    }
}
