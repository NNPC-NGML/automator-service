<?php

namespace App\Jobs\DesignationAssignment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Skillz\Nnpcreusable\Models\DesignationUser;

class DesignationAssignmentCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function handle(): void
    {
        $model = new DesignationUser();
        $model->create($this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
