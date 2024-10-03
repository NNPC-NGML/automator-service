<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Unit;
use App\Models\User;
use App\Models\Routes;
use App\Models\Location;
use App\Models\Department;
use App\Models\Designation;
use App\Models\ProcessFlow;
use App\Models\CustomerSite;
use App\Models\AutomatorTask;
use App\Models\ProcessFlowStep;
use App\Models\ProcessFlowHistory;
use App\Service\AutomatorTaskService;
use Skillz\Nnpcreusable\Models\HeadOfUnit;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Skillz\Nnpcreusable\Service\UserService;

class AutomatorTaskControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_all_task_assigned_to_a_particular_head_of_unit(): void
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
        (new AutomatorTaskService())->newTaskFromPreviousTask($automatorTask->toArray());
        $this->actingAsAuthenticatedTestUser();
        $response = $this->getJson('/api/unassignedtasks');
        $response->assertOk()->assertJsonStructure([
            "data" => [
                "*" => [
                    "processflow_step",
                    "processflow",
                    "task_status",
                    "assignment_status",
                    "processflow_history",
                    "entity",
                    "entity_site_id",
                ],

            ],
        ]);
    }
    public function test_get_all_user_under_a_particular_head_of_unit_based_on_a_particular_unassigned_head_of_unit_task(): void
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

        // add root user to department unit location designation
        (new UserService())->assignUserToUnit($user->id, $unit->id);
        (new UserService())->assignUserToLocation($user->id, $location->id);
        (new UserService())->assignUserToDepartment($user->id, $department->id);
        (new UserService())->assignUserToDesignation($user->id, $designation->id);
        //$data = AutomatorTask::where(["id" => 1])->with([])->first();
        // create 2 new users 
        $user2 = User::factory()->create();
        (new UserService())->assignUserToUnit($user2->id, $unit->id);
        (new UserService())->assignUserToLocation($user2->id, $location->id);
        (new UserService())->assignUserToDepartment($user2->id, $department->id);
        (new UserService())->assignUserToDesignation($user2->id, $designation->id);

        $user3 = User::factory()->create();
        (new UserService())->assignUserToUnit($user3->id, $unit->id);
        (new UserService())->assignUserToLocation($user3->id, $location->id);
        (new UserService())->assignUserToDepartment($user3->id, $department->id);
        (new UserService())->assignUserToDesignation($user3->id, $designation->id);

        $newAutomatorTask=(new AutomatorTaskService())->newTaskFromPreviousTask($automatorTask->toArray());
        $this->actingAsAuthenticatedTestUser();
        $response = $this->getJson('/api/task-assignable-users/' . $newAutomatorTask->id);
        $response->assertOk()->assertJsonStructure([
            "data" => [
                "*" => [
                    "processflow_step",
                    "processflow",
                    "task_status",
                    "assignment_status",
                    "processflow_history",
                    "entity",
                    "entity_site_id",
                ],

            ],
        ]);
    }
    public function test_assign_a_task_from_head_of_unit_to_subordinate(): void {}
}
