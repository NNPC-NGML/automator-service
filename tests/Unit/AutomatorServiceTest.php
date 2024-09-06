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

class AutomatorServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test to create a new task using automatortaskservice
     */
    public function test_to_see_if_a_new_automatortask_can_be_created(): void
    {
        $data = [
            "formbuilder_data_id" => 1,
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
        ];
        $automatorService = (new AutomatorTaskService())->createTask($data);
        $this->assertDatabaseHas('automator_tasks', $data);
        $this->assertInstanceOf(AutomatorTask::class, $automatorService);
    }

    public function test_a_task_can_be_updated(): void
    {
        //create new dummy task
        $createTask = AutomatorTask::factory()->create();
        // get the task 
        $getTask = AutomatorTask::find($createTask->id);
        //update task
        $data = [
            "id" => $getTask->id,
            "formbuilder_data_id" => 1,
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
            "task_status" => 1,
        ];
        (new AutomatorTaskService())->updateTask($data);
        $this->assertDatabaseHas('automator_tasks', $data);
        //$this->assertInstanceOf(AutomatorTask::class, $automatorService);

    }

    public function test_that_a_task_can_be_created_from_previous_task(): void
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
        //$data = AutomatorTask::where(["id" => 1])->with([])->first();
        $response = (new AutomatorTaskService)->newTaskFromPreviousTask($automatorTask->toArray());
        // assert that there is two task
        // asser that the respose is true
        $this->assertInstanceOf(AutomatorTask::class, $response);
        $this->assertDatabaseCount("automator_tasks", 2);
    }
}
