<?php

namespace App\Jobs\ProcessFlowHistory;

use App\Models\AutomatorTask;
use Illuminate\Bus\Queueable;
use App\Service\AutomatorTaskService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use App\Service\ProcessFlowHistoryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\AutomatorTask\AutomatorTaskCreated;
use App\Jobs\AutomatorTask\AutomatorTaskUpdated;
use App\Jobs\NewTaskFromPreviousTask\AutomatorCreateQueue;

class ProcessFlowHistoryUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for updating the ProcessFlowhistory.
     *
     * @var array
     */
    private array $data;
    private int $id;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->id = $data['id'];
    }

    // public function handle(): void
    // {
    //     Artisan::call('app:automator-task', ['data' => $this->data]);
    //     $service = new  \Skillz\Nnpcreusable\Service\ProcessFlowHistoryService();
    //     $data = new \Illuminate\Http\Request($this->data);
    //     $service->updateProcessFlowHistory($data, $this->id);
    // }
    public function handle(): void
    {
        //Artisan::call('app:automator-task', ['data' => $this->data]);
        $jobData = [
            "processflow_history_id" => $this->data["id"],
            "formbuilder_data_id" => $this->data["formbuilder_data_id"],
            "entity" => $this->data["for"],
            "entity_id" => $this->data["for_id"],
            "entity_site_id" => $this->data["for_site_id"],
            "user_id" => $this->data["user_id"],
            "processflow_id" => $this->data["process_flow_id"],
            "processflow_step_id" => $this->data["processflow_step_id"],
            "form_builder_id" => $this->data["form_builder_id"],
            "task_id" => $this->data["task_id"],

        ];

        //convert data
        $service = new AutomatorTaskService();
        if (empty($jobData["task_id"])) {
            //restructure data to be able to fit into automator task table 
            $taskData = $jobData;
            $taskData["task_status"] = AutomatorTask::COMPLETED;
            $createTask = $service->createTask($taskData);

            //dispatch to formbuilder service and processflow service
            foreach (config("nnpcreusable.AUTOMATOR_TASK_CREATED") as $queue) {
                AutomatorTaskCreated::dispatch($createTask->toArray())->onQueue($queue);
            }
            // push to queue to start another process
            //AutomatorCreateQueue::dispatch($createTask->toArray())->onQueue("automator_queue");
        }

        if (!empty($jobData["task_id"])) {
            // update task with the processflow history id
            $taskData = $jobData;
            $updateTask = $service->updateTask($jobData["task_id"], $taskData);
            //dispatch to formbuilder service and processflow service
            if ($updateTask) {
                $getTask = $service->getTask($jobData["task_id"]);
                foreach (config("nnpcreusable.AUTOMATOR_TASK_UPDATED") as $queue) {
                    AutomatorTaskUpdated::dispatch($getTask->toArray())->onQueue($queue);
                }
                $newService = new  \Skillz\Nnpcreusable\Service\ProcessFlowHistoryService();
                $data = new \Illuminate\Http\Request($this->data);
                $newService->updateProcessFlowHistory($data, $this->id);
            }
        }
    }
}
