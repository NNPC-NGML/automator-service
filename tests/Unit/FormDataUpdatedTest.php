<?php

namespace Tests\Unit\Jobs\ProcessFlowHistory;

use Tests\TestCase;
use App\Models\ProcessFlowHistory;
use App\Service\AutomatorTaskService;
use Illuminate\Support\Facades\Queue;
use App\Jobs\FormData\FormDataUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\NewTaskFromPreviousTask\AutomatorCreateQueue;



class FormDataUpdatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_a_task_can_be_updated_from_form_data_updated()
    {
        // create a new processflow history 
        $createdHistory = ProcessFlowHistory::factory()->create();
        // create a task
        $newTaskData = [
            "entity" => 1,
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
            "task_status" => 0,
            "processflow_history_id" => $createdHistory->id
        ];
        $service = new AutomatorTaskService();
        $service->createTask($newTaskData);

        $this->assertDatabaseCount("automator_tasks", 1);

        $data = [
            'id' => 1,
            'form_builder_id' => 1,
            'form_field_answers' => ["abc" => 1],
            'automator_task_id' => 1,
            'process_flow_history_id' => 1,
            'entity' => 1,
            'entity_site_id' => null,
            'entity_id' => 1,
            'user_id' => 1,
            'status' => 0,
            "formBuilder" => [
                'id' => 1,
                'name' => "test",
                'process_flow_id' => 1,
                'process_flow_step_id' => 1,
                'tag_id' => 1,
            ]
            // "id" => $createdHistory->id,
            // "task_id" => 1, // $task->id,
            // "for_site_id" => 1,
            // "formbuilder_data_id" => 1,
            // "step_id" => 1,
            // "process_flow_id" => 1,
            // "user_id" => 1,
            // "processflow_step_id" => 1,
            // "for" => 1,
            // "for_id" => 1,
            // "form_builder_id" => 1,
            // "approval" => 1,
            // "status" => 1,
        ];
        $job = new FormDataUpdated($data);
        $job->handle();
        $this->assertDatabaseHas("automator_tasks", ["formbuilder_data_id" => 1]);
    }
    public function test_that_a_task_has_been_completed()
    {
        // create a new processflow history 
        $createdHistory = ProcessFlowHistory::factory()->create();
        // create a task
        $newTaskData = [
            "entity" => 1,
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
            "task_status" => 0,
            "processflow_history_id" => $createdHistory->id
        ];
        $service = new AutomatorTaskService();
        $service->createTask($newTaskData);

        $this->assertDatabaseCount("automator_tasks", 1);

        $data = [
            'id' => 1,
            'form_builder_id' => 1,
            'form_field_answers' => ["abc" => 1],
            'automator_task_id' => 1,
            'process_flow_history_id' => 1,
            'entity' => "customer",
            'entity_site_id' => null,
            'entity_id' => 1,
            'user_id' => 1,
            'status' => 0,
            "formBuilder" => [
                'id' => 1,
                'name' => "test",
                'process_flow_id' => 1,
                'process_flow_step_id' => 1,
                'tag_id' => 1,
            ]
        ];
        $job = new FormDataUpdated($data);
        $job->handle();
        $this->assertDatabaseHas("automator_tasks", ["task_status" => 1]);
    }
    public function test_that_a_new_task_can_be_created_from_the_previous_task()
    {
        Queue::fake();
        // create a new processflow history 
        $createdHistory = ProcessFlowHistory::factory()->create();
        // create a task
        $newTaskData = [
            "entity" => 1,
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
            "task_status" => 0,
            "processflow_history_id" => $createdHistory->id
        ];
        $service = new AutomatorTaskService();
        $service->createTask($newTaskData);

        $this->assertDatabaseCount("automator_tasks", 1);

        $data = [
            'id' => 1,
            'form_builder_id' => 1,
            'form_field_answers' => ["abc" => 1],
            'automator_task_id' => 1,
            'process_flow_history_id' => 1,
            'entity' => "customer",
            'entity_site_id' => null,
            'entity_id' => 1,
            'user_id' => 1,
            'status' => 1,
            "formBuilder" => [
                'id' => 1,
                'name' => "test",
                'process_flow_id' => 1,
                'process_flow_step_id' => 1,
                'tag_id' => 1,
            ]
        ];
        $job = new FormDataUpdated($data);
        $job->handle();
        Queue::assertPushed(AutomatorCreateQueue::class, function ($job) {
            return $job->queue === 'automator_queue';
        });
    }
}
