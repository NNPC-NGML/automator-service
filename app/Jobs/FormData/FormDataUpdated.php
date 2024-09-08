<?php

namespace App\Jobs\FormData;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use App\Services\AutomatorTaskService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\AutomatorTask\AutomatorTaskUpdated;
use App\Jobs\NewTaskFromPreviousTask\AutomatorCreateQueue;
use App\Service\AutomatorTaskService as ServiceAutomatorTaskService;

class FormDataUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $data;
    private int $id;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // match incoming data key with automator task data key 
        $jobData = [
            //formBuilder
            "processflow_history_id" => $this->data["process_flow_history_id"],
            "formbuilder_data_id" => $this->data["id"],
            "entity" => $this->data["entity"],
            "entity_id" => $this->data["entity_id"],
            "entity_site_id" => $this->data["entity_site_id"],
            "user_id" => $this->data["user_id"],
            "processflow_id" => $this->data["formBuilder"]["process_flow_id"],
            "processflow_step_id" => $this->data["formBuilder"]["process_flow_step_id"],
            "form_builder_id" => $this->data["formBuilder"]["id"],
            "task_id" => $this->data["automator_task_id"],

        ];
        $service = new ServiceAutomatorTaskService();
        if (!$this->data["status"]) {
            $updateTask = $service->updateTask($jobData["task_id"], $jobData);
            //dispatch to formbuilder service and processflow service
            if ($updateTask) {
                $getTask = $service->getTask($jobData["task_id"]);
                $status = true;
                $fields = [
                    "processflow_history_id",
                    "formbuilder_data_id",
                    "entity",
                    "entity_id",
                    "user_id",
                    "processflow_id",
                    "processflow_step_id",
                ];

                foreach ($fields as $field) {
                    if (!isset($getTask->$field) || is_null($getTask->$field)) {
                        $status = false;
                    }
                }
                if ($status) {
                    $service->updateTask($jobData["task_id"], ["task_status" => 1]);
                    $getTask = $service->getTask($jobData["task_id"]);
                }


                foreach (config("nnpcreusable.AUTOMATOR_TASK_UPDATED") as $queue) {
                    AutomatorTaskUpdated::dispatch($getTask->toArray())->onQueue($queue);
                }
            }
        }

        if ($this->data["status"]) {
            $getTask = $service->getTask($jobData["task_id"]);
            //if the status is 1  then push to AutomatorCreateQueue
            AutomatorCreateQueue::dispatch($getTask->toArray())->onQueue("automator_queue");
        }
    }
}
