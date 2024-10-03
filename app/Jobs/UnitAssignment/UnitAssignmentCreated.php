<?php

namespace App\Jobs\UnitAssignment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Skillz\Nnpcreusable\Models\UnitUser;

class UnitAssignmentCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function handle(): void
    {
        $model = new UnitUser();
        $model->create($this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
