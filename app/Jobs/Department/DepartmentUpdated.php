<?php

namespace App\Jobs\Department;

use Illuminate\Bus\Queueable;
use Skillz\Nnpcreusable\Service\DepartmentService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DepartmentUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for updating the department.
     *
     * @var array
     */
    private array $data;

    /**
     * The ID of the department to be updated.
     *
     * @var int
     */
    private int $id;

    /**
     * Create a new job instance.
     *
     * @param array $data
     * @param int $id
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->id = $data['id'];
    }

    /**
     * Get the data for updating the department.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the ID of the department to be updated.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
        $service->updateDepartment($this->data, $this->id);
        // try {
        // } catch (\Exception $e) {
        //     Log::error('Error occurred while processing DepartmentUpdated job: ' . $e->getMessage());
        // }
    }
}
