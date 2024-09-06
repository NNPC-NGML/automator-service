<?php

namespace App\Jobs\ProcessFlowHistory;

use App\Models\AutomatorTask;
use Illuminate\Bus\Queueable;
use App\Service\AutomatorTaskService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\AutomatorTask\AutomatorTaskCreated;
use App\Jobs\NewTaskFromPreviousTask\AutomatorCreateQueue;

class ProcessFlowHistoryCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for creating the ProcessFlowhistory.
     *
     * @var array
     */
    private array $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        //Artisan::call('app:automator-task', ['data' => $this->data]);
        $jobData = $this->data;
        if (empty($jobData["task_id"])) {
            $createService = new AutomatorTaskService();
            //restructure data to be able to fit into automator task table 
            $taskData = $this->data;
            $taskData["status"] = AutomatorTask::COMPLETED;
            $createTask = $createService->createTask($taskData);
            //dispatch to formbuilder service and processflow service
            foreach (config("nnpcreusable.AUTOMATOR_TASK_CREATED") as $queue) {
                AutomatorTaskCreated::dispatch($createTask->toArray())->onQueue($queue);
            }
            // push to queue to start another process
            AutomatorCreateQueue::dispatch($createTask->toArray())->onQueue("automator_queue");
        }

        if (!empty($jobData["task_id"])) {
            // update task with the processflow history id
        }

        $service = new  \Skillz\Nnpcreusable\Service\ProcessFlowHistoryService();
        $data = new \Illuminate\Http\Request($this->data);
        $service->createProcessFlowHistory($data);
    }
}
