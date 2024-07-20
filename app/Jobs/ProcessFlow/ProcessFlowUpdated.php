<?php

namespace App\Jobs\ProcessFlow;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Skillz\Nnpcreusable\Service\ProcessFlowService;

class ProcessFlowUpdated implements ShouldQueue
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
        $service = new  ProcessFlowService();
        $data = new Request($this->data);
        $service->updateProcessFlow($this->id, $data);
    }
}
