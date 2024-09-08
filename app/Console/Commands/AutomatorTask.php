<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\AutomatorTaskService;
use App\Jobs\AutomatorTask\AutomatorTaskCreated;


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
        if (empty($data['task_id'])) {
            //study argument data to know if to create a new task or to update task using processflow history id
            if ($task = $createService->createTask($data)) {
                // push created task to all the queue that needs the data 
                foreach (config("nnpcreusable.AUTOMATOR_TASK_CREATED") as $queue) {
                    AutomatorTaskCreated::dispatch($task->toArray())->onQueue($queue);
                }

                $this->info("Task created successfully");
            } else {
                $this->info("Sorry could not create or update task");
            }
        }
    }
}
