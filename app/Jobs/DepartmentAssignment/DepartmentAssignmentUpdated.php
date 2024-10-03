<?php

namespace App\Jobs\DepartmentAssignment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Skillz\Nnpcreusable\Models\DepartmentUser;

class DepartmentAssignmentUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private array $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function handle(): void
    {
        $model = DepartmentUser::where(["id" => $this->data["id"]])->first();
        if ($model) {
            $model->update($this->data);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }
}
