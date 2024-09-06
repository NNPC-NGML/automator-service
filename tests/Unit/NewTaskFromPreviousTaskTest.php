<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Routes;
use App\Models\ProcessFlow;
use App\Models\AutomatorTask;
use App\Models\ProcessFlowStep;
use App\Models\ProcessFlowHistory;
use App\Service\AutomatorTaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\NewTaskFromPreviousTask\AutomatorCreateQueue;

class NewTaskFromPreviousTaskTest extends TestCase
{
    use RefreshDatabase;
    public function test_that_a_new_task_can_be_created_from_a_previous_task(): void
    {
        //create a processflow 
        $createProcessflow = ProcessFlow::factory()->create([
            "id" => 1,
            "start_step_id" => 1
        ]);
        $route = Routes::factory()->create();
        $processFlowStep = ProcessFlowStep::factory()->create([
            "step_route" => $route->id,
            "process_flow_id" => $createProcessflow->id,
            "id" => 1,
            "next_step_id" => 2,
            "next_user_designation" => 0,
        ]);
        ProcessFlowStep::factory()->create([
            "step_route" => $route->id,
            "process_flow_id" => $createProcessflow->id,
            "id" => 2,
            "next_step_id" => 3,
        ]);

        //create first task 
        $automatorTask = AutomatorTask::factory()->create([
            "processflow_history_id" => 1,
            "formbuilder_data_id" => 1,
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
            "task_status" => 1
        ]);

        //create first history
        ProcessFlowHistory::factory()->create([
            "id" => 1,
            "task_id" => 1,
            "step_id" => 1,
            "process_flow_id" => 1,
            "user_id" => 1,
            "form_builder_id" => 1,
            "approval" => 1,
            "status" => 1,
        ]);

        $response = new AutomatorCreateQueue($automatorTask->toArray());
        $response->handle();

        //$this->assertInstanceOf(AutomatorTask::class, $response);
        $this->assertDatabaseCount("automator_tasks", 2);
    }
}
