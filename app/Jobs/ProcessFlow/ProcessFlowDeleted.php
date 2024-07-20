<?php

namespace App\Jobs\ProcessFlow;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Skillz\Nnpcreusable\Service\ProcessFlowService;

class ProcessFlowDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle(): void
    {
        $service = new  ProcessFlowService();
        $service->deleteProcessFlow($this->id);
    }
}
