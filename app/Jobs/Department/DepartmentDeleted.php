<?php

namespace App\Jobs\Department;

use Illuminate\Bus\Queueable;
use Skillz\Nnpcreusable\Service\DepartmentService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DepartmentDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    /**
     * Execute the job.
     *
     * @param  DepartmentService $service
     * @return void
     */
    public function handle(): void
    {

        $service = new  DepartmentService();
        $service->deleteDepartment($this->id);
    }

    public function getId(): int
    {
        return $this->id;
    }
}
