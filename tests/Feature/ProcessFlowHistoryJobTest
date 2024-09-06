<?php

namespace Tests\Feature;

use App\Jobs\Automator\AutomatorTaskBroadcasterJob;
use App\Jobs\ProcessflowServiceJobs\ProcessflowHistoryJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class ProcessFlowHistoryJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    // public function test_it_handles_automation_task_creation_job(): void
    // {
    //     Queue::fake();

    //     $data = [
    //         "formbuilder_data_id" => 1,
    //         "customer_id" => 1,
    //         "user_id" => 1,
    //         "processflow_id" => 1,
    //         "processflow_step_id" => 1,
    //     ];

    //     $job = new ProcessflowHistoryJob($data);
    //     $job->handle();

    //     $this->assertDatabaseCount('automator_tasks', 1);
    //     $this->assertDatabaseHas('automator_tasks', $data);
    // }
    // public function test_to_dispatches_automator_task_broadcaster_job_functionality(): void
    // {
    //     Queue::fake();

    //     $request = [
    //         'task_id' => 1,
    //         'processflow_history_id' => 5634,
    //         'customer_id' => 1,
    //         'step_id' => 1,
    //         'route_id' => 1,
    //         'user_id' => 1,
    //         'task_duration' => 1,
    //         'task_status' => 0,
    //     ];
    //     AutomatorTaskBroadcasterJob::dispatch($request);

    //     Queue::assertPushed(AutomatorTaskBroadcasterJob::class, function ($job) use ($request) {
    //         return $job->data == $request;
    //     });
    // }
}
