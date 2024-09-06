<?php

namespace App\Jobs\ProcessflowStep;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessflowStepUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $data;
    private int $id;
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->id = $data['id'];
    }

    public function handle(): void
    {
        $service = new  \Skillz\Nnpcreusable\Service\ProcessflowStepService();
        $data = new \Illuminate\Http\Request($this->data);
        $service->updateProcessflowStep($data, $this->id);
    }
}
