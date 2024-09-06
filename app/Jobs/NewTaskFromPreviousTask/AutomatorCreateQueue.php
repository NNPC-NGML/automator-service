<?php

namespace App\Jobs\NewTaskFromPreviousTask;

use App\Models\AutomatorTask;
use Illuminate\Bus\Queueable;
use App\Service\AutomatorTaskService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\AutomatorTask\AutomatorTaskCreated;

class AutomatorCreateQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // create a new task
        $response = (new AutomatorTaskService)->newTaskFromPreviousTask($this->data);

        if ($response) {

            //publish response to jobs
            //fetch new task
            $newTask = AutomatorTask::where(["id" => $response->id])
                ->with([
                    "processflowHistory",
                    "processflow",
                    "processflowStep.route",
                ])->first();
            $convertedRoute = $this->routeConverter($newTask);

            $data = $newTask->toArray();
            $data["route"] = $convertedRoute;
            // get all task relationship, structure the route convert to array add new keys to array like route url and then push to other services 
            foreach (config("nnpcreusable.AUTOMATOR_TASK_CREATED") as $queue) {
                AutomatorTaskCreated::dispatch($data)->onQueue($queue);
            }
        }
    }

    private function routeConverter($data)
    {
        $routeData = $data->processflowStep->route;
        $route = $routeData->link;
        foreach (json_decode($routeData->dynamic_content)  as $dynamicRoute) {
            switch ($dynamicRoute) {
                case "customer_id":
                    $route = $route . "/" . $data->entity_id;
                    break;
                case "customer_site_id":
                    $route = $route . "/" . $data->entity_site_id;
                    break;
            }
        }

        return $route;
    }
}
