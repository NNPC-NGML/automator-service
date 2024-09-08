<?php

namespace Tests\Unit\Jobs\ProcessFlowHistory;

use Tests\TestCase;
use App\Models\AutomatorTask;
use App\Models\ProcessFlowHistory;
use App\Service\AutomatorTaskService;
use App\Jobs\AutomatorTask\AutomatorTaskCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\NewTaskFromPreviousTask\AutomatorCreateQueue;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryUpdated;
use Skillz\Nnpcreusable\Service\ProcessFlowHistoryService;

class ProcessFlowHistoryUpdatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_a_task_can_be_created_from_process_flow_history_updated()
    {
        $this->assertDatabaseCount("automator_tasks", 0);
        $data = [
            "id" => 1,
            "task_id" => null,
            "for_site_id" => 1,
            "formbuilder_data_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "processflow_step_id" => 1,
            "for" => 1,
            "for_id" => 1,
            "form_builder_id" => 1,
            "approval" => 1,
            "status" => 1,
        ];
        $job = new ProcessFlowHistoryUpdated($data);
        $job->handle();
        $this->assertDatabaseCount("automator_tasks", 1);
    }
    public function test_that_a_task_can_be_updated_from_process_flow_history_updated()
    {
        // create a new processflow history 
        $historyData = [
            "for_site_id" => 1,
            "formbuilder_data_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "processflow_step_id" => 1,
            "for" => 1,
            "for_id" => 1,
            "form_builder_id" => 1,
            "approval" => 1,
            "status" => 1,
        ];

        $createdHistory = ProcessFlowHistory::factory()->create();
        // create a task
        $newTaskData = [
            "formbuilder_data_id" => 1,
            "entity" => 1,
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
            "task_status" => 0,
        ];
        $service = new AutomatorTaskService();
        $service->createTask($newTaskData);

        $this->assertDatabaseCount("automator_tasks", 1);
        $this->assertDatabaseHas("automator_tasks", ["processflow_history_id" => null]);
        $data = [
            "id" => $createdHistory->id,
            "task_id" => 1, // $task->id,
            "for_site_id" => 1,
            "formbuilder_data_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "processflow_step_id" => 1,
            "for" => 1,
            "for_id" => 1,
            "form_builder_id" => 1,
            "approval" => 1,
            "status" => 1,
        ];
        $job = new ProcessFlowHistoryUpdated($data);
        $job->handle();
        $this->assertDatabaseHas("automator_tasks", ["processflow_history_id" => $createdHistory->id]);
    }
}
