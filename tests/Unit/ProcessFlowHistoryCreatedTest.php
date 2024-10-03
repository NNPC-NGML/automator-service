<?php

namespace Tests\Unit\ProcessFlowHistory;

use Tests\TestCase;
use App\Models\AutomatorTask;
use App\Jobs\ProcessFlowHistory\ProcessFlowHistoryCreated;
use App\Service\AutomatorTaskService;
use App\Jobs\AutomatorTask\AutomatorTaskCreated;
use App\Jobs\NewTaskFromPreviousTask\AutomatorCreateQueue;
use Skillz\Nnpcreusable\Service\ProcessFlowHistoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessFlowHistoryCreatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_a_task_can_be_created_from_process_flow_history()
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
        $job = new ProcessFlowHistoryCreated($data);
        $job->handle();
        $this->assertDatabaseCount("automator_tasks", 1);
    }
    public function test_that_a_task_can_be_updated_from_process_flow_history()
    {
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
        $task = $service->createTask($newTaskData);

        $this->assertDatabaseCount("automator_tasks", 1);
        $this->assertDatabaseHas("automator_tasks", ["processflow_history_id" => null]);
        $data = [
            "id" => 1,
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
        $job = new ProcessFlowHistoryCreated($data);
        $test = $job->handle();
        $this->assertDatabaseHas("automator_tasks", ["processflow_history_id" => 1]);
    }
}
