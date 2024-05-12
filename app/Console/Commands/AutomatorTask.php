<?php

namespace App\Console\Commands;

use App\Jobs\Automator\AutomatorTaskBroadcasterJob;
use App\Service\AutomatorTaskService;
use Illuminate\Console\Command;

class AutomatorTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automator-task {data*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update a new automator task';

    /**
     * Execute the console command to create or update an existing automator task 
     */
    public function handle()
    {
        $data = $this->argument('data');
        $createService = new AutomatorTaskService();
        //study argument data to know if to create a new task or to update task using processflow history id
        if ($task = $createService->createTask($data)) {
            // pushcreated task to all the queue that needs the data 
            AutomatorTaskBroadcasterJob::dispatch($task->toArray())->onQueue(["processflow-service", "formbuilder_service", "notification_service"]);

            $this->info("Task created successfully");
        } else {
            $this->info("Sorry could not create or update task");
        }
    }
}
