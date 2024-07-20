<?php

namespace App\Jobs\ProcessFlow;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Skillz\Nnpcreusable\Models\ProcessFlow;
use Skillz\Nnpcreusable\Service\ProcessFlowService;

class ProcessFlowCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */


    private $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new  ProcessFlowService();
        $data = new Request($this->data);
        $service->createProcessFlow($data);
    }
}
