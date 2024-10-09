<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Unit;
use App\Models\User;
use App\Models\Routes;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Department;
use App\Models\Designation;
use App\Models\ProcessFlow;
use App\Models\CustomerSite;
use App\Models\AutomatorTask;
use App\Models\ProcessFlowStep;
use App\Models\ProcessFlowHistory;
use App\Service\AutomatorTaskService;
use Illuminate\Support\Facades\Queue;
use Skillz\Nnpcreusable\Models\HeadOfUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\AutomatorTask\AutomatorTaskCreateAssign;
use App\Jobs\AutomatorTask\AutomatorProcessFlowFrequency;
use Skillz\Nnpcreusable\Service\UserService;

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
            "formbuilder_data_id" => 1,
            "entity_id" => 1,
            "entity_site_id" => 1,
            "user_id" => 1,
            "processflow_id" => 1,
            "processflow_step_id" => 1,
            "task_status" => 1,
        ];
        (new AutomatorTaskService())->updateTask($getTask->id, $data);
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

    public function test_that_head_of_unit_can_be_fetched()
    {
        $location =  Location::factory()->create();
        $department = Department::factory()->create();
        $unit = Unit::factory()->create();
        $designation = Designation::factory()->create();
        $data = [
            "location" => $location->id,
            "department" => $department->id,
            "unit" => $unit->id,
            "designation" => $designation->id,
        ];
        // create a user 
        $user = User::factory()->create();
        // create head of unit 
        (new HeadOfUnit())->create([
            "user_id" => $user->id,
            "location_id" => $location->id,
            "unit_id" => $unit->id,
        ]);
        $response = (new AutomatorTaskService)->getHeadOfUnit($data);
        $this->assertEquals($user->id, $response);
    }

    public function test_that_a_task_can_be_created_from_previous_task_and_assign_to_head_of_unit(): void
    {
        $location =  Location::factory()->create();
        $department = Department::factory()->create();
        $unit = Unit::factory()->create();
        $designation = Designation::factory()->create();
        $customerSite = CustomerSite::factory()->create([
            "ngml_zone_id" => $location->id
        ]);
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
            "next_user_designation" => $designation->id,
            "next_user_unit" => $unit->id,
        ]);
        ProcessFlowStep::factory()->create([
            "step_route" => $route->id,
            "process_flow_id" => $createProcessflow->id,
            "id" => 2,
            "next_step_id" => 3,
            "next_user_designation" => $designation->id,
            "next_user_unit" => $unit->id,
        ]);

        //create first task 
        $automatorTask = AutomatorTask::factory()->create([
            "processflow_history_id" => 1,
            "formbuilder_data_id" => 1,
            "entity_id" => 1,
            "entity_site_id" => $customerSite->id,
            "user_id" => 8,
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
        $user = User::factory()->create();
        // create head of unit 
        (new HeadOfUnit())->create([
            "user_id" => $user->id,
            "location_id" => $location->id,
            "unit_id" => $unit->id,
        ]);
        //$data = AutomatorTask::where(["id" => 1])->with([])->first();
        $response = (new AutomatorTaskService())->newTaskFromPreviousTask($automatorTask->toArray());

        // assert that there is two task
        // asser that the respose is true
        $this->assertInstanceOf(AutomatorTask::class, $response);
        $this->assertDatabaseCount("automator_tasks", 2);
    }

    public function test_task_can_be_assigned_to_a_user()
    {
        $user = User::factory()->create();
        $task = AutomatorTask::factory()->create();
        $response = (new AutomatorTaskService())->assignTaskToUser($task->id, $user->id, $user->id);
        $this->assertInstanceOf(AutomatorTask::class, $response);
        $this->assertDatabaseHas("automator_tasks", ["user_id" => $user->id]);
    }

    public function test_process_flow_frequency_can_be_dispatched_for_possible_execution()
    {
        Queue::fake();
        // create multiple process flow  
        ProcessFlow::factory(3)->create([
            //"id" => 1,
            "start_step_id" => 1,
            "frequency" => "daily",
            "frequency_for" => "customers",
        ]);

        (new AutomatorTaskService())->processFlowFrequency();
        // ensure that all task is dispatched 
        Queue::assertPushed(AutomatorProcessFlowFrequency::class, 3);
    }

    public function test_automator_can_process_flow_frequency_job_to_queue_customer_for_each_processflow()
    {
        Queue::fake();
        $processFlow = ProcessFlow::factory()->create([
            "id" => 1,
            "start_step_id" => 1,
            "frequency" => "daily",
            "frequency_for" => "customers",
        ]);

        $customer = Customer::factory()->create(["id" => 1, "status" => true]);

        CustomerSite::factory()->create([
            "id" => 1,
            "customer_id" => $customer->id
        ]);

        $job = new AutomatorProcessFlowFrequency($processFlow->toArray());
        $job->handle();

        Queue::assertPushed(AutomatorTaskCreateAssign::class, 1);
    }

    public function test_handle_frequency_for_customer_dispatches_job_to_queue()
    {
        Queue::fake();
        $processFlow = ProcessFlow::factory()->create([
            "id" => 1,
            "start_step_id" => 1,
            "frequency" => "daily",
            "frequency_for" => "customers",
        ]);

        $customer = Customer::factory()->create([
            "id" => 1,
            "status" => true
        ]);

        CustomerSite::factory()->create([
            "id" => 1,
            "customer_id" => $customer->id
        ]);

        $findout = (new AutomatorTaskService())->handleFrequencyForCustomer($processFlow->toArray());
        //dd($findout);
        Queue::assertPushed(AutomatorTaskCreateAssign::class, 1);
    }

    public function test_that_a_task_can_be_created_for_each_processflow_frequency()
    {
        $location =  Location::factory()->create();
        $department = Department::factory()->create();
        $unit = Unit::factory()->create();
        $designation = Designation::factory()->create();
        $customer = Customer::factory()->create();
        $customerSite = CustomerSite::factory()->create([
            "ngml_zone_id" => $location->id,
            "customer_id" => $customer->id
        ]);
        //create a processflow 
        $createProcessflow = ProcessFlow::factory()->create([
            "id" => 1,
            "start_step_id" => 1,
            "start_user_designation" => $designation->id,
            "start_user_department" => $department->id,
            "start_user_unit" => $unit->id
        ]);
        $route = Routes::factory()->create();
        ProcessFlowStep::factory()->create([
            "step_route" => $route->id,
            "process_flow_id" => $createProcessflow->id,
            "id" => 1,
            "next_step_id" => 2,
            "next_user_designation" => $designation->id,
            "next_user_unit" => $unit->id,
        ]);
        ProcessFlowStep::factory()->create([
            "step_route" => $route->id,
            "process_flow_id" => $createProcessflow->id,
            "id" => 2,
            "next_step_id" => 3,
            "next_user_designation" => $designation->id,
            "next_user_unit" => $unit->id,
        ]);


        $user = User::factory()->create();
        (new UserService())->assignUserToUnit($user->id, $unit->id);
        (new UserService())->assignUserToLocation($user->id, $location->id);
        (new UserService())->assignUserToDepartment($user->id, $department->id);
        (new UserService())->assignUserToDesignation($user->id, $designation->id);
        // create head of unit 
        (new HeadOfUnit())->create([
            "user_id" => $user->id,
            "location_id" => $location->id,
            "unit_id" => $unit->id,
        ]);

        $data = [
            "entity" => $customerSite->toArray(),
            "processflow" => $createProcessflow->toArray()
        ];

        (new AutomatorTaskService())->createAndAssignTaskToHeadOfUnit($data);
        // assert that there is two task
        // asser that the respose is true
        $this->assertDatabaseCount("automator_tasks", 1);
    }
    public function test_that_a_task_can_be_created_for_each_processflow_frequency_job()
    {
        $location =  Location::factory()->create();
        $department = Department::factory()->create();
        $unit = Unit::factory()->create();
        $designation = Designation::factory()->create();
        $customer = Customer::factory()->create();
        $customerSite = CustomerSite::factory()->create([
            "ngml_zone_id" => $location->id,
            "customer_id" => $customer->id
        ]);
        //create a processflow 
        $createProcessflow = ProcessFlow::factory()->create([
            "id" => 1,
            "start_step_id" => 1,
            "start_user_designation" => $designation->id,
            "start_user_department" => $department->id,
            "start_user_unit" => $unit->id
        ]);
        $route = Routes::factory()->create();
        ProcessFlowStep::factory()->create([
            "step_route" => $route->id,
            "process_flow_id" => $createProcessflow->id,
            "id" => 1,
            "next_step_id" => 2,
            "next_user_designation" => $designation->id,
            "next_user_unit" => $unit->id,
        ]);
        ProcessFlowStep::factory()->create([
            "step_route" => $route->id,
            "process_flow_id" => $createProcessflow->id,
            "id" => 2,
            "next_step_id" => 3,
            "next_user_designation" => $designation->id,
            "next_user_unit" => $unit->id,
        ]);


        $user = User::factory()->create();
        (new UserService())->assignUserToUnit($user->id, $unit->id);
        (new UserService())->assignUserToLocation($user->id, $location->id);
        (new UserService())->assignUserToDepartment($user->id, $department->id);
        (new UserService())->assignUserToDesignation($user->id, $designation->id);
        // create head of unit 
        (new HeadOfUnit())->create([
            "user_id" => $user->id,
            "location_id" => $location->id,
            "unit_id" => $unit->id,
        ]);

        $data = [
            "entity" => $customerSite->toArray(),
            "processflow" => $createProcessflow->toArray()
        ];

        $job = new AutomatorTaskCreateAssign($data);
        $job->handle();
        // assert that there is two task
        // asser that the respose is true
        $this->assertDatabaseCount("automator_tasks", 1);
    }
}
