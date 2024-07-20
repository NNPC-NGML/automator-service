<?php

namespace App\Jobs\ProcessFlowHistory;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ProcessFlowHistoryCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for creating the ProcessFlowhistory.
     *
     * @var array
     */
    private array $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        Artisan::call('app:automator-task', ['data' => $this->data]);
        $service = new  \Skillz\Nnpcreusable\Service\ProcessFlowHistoryService();
        $data = new \Illuminate\Http\Request($this->data);
        $service->createProcessFlowHistory($data);
    }
}
