<?php

namespace App\Jobs\Department;

use Skillz\Nnpcreusable\Models\Department;
use Illuminate\Bus\Queueable;
use Skillz\Nnpcreusable\Service\DepartmentService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DepartmentCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for creating the unit.
     *
     * @var array
     */
    public array $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param DepartmentService $service
     * @return void
     */
    public function handle(): void
    {

        $service = new  DepartmentService();
        $service->createDepartment($this->data);

        // try {
        //     $service->createDepartment($this->data);
        // } catch (\Exception $e) {
        //     Log::error('Error occurred while processing DepartementCreated job: ' . $e->getMessage());
        // }
    }

    public function getData(): array
    {
        return $this->data;
    }
}
