<?php

namespace App\Jobs\ProcessFlowHistory;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ProcessFlowHistoryDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle(): void
    {
        $service = new  \Skillz\Nnpcreusable\Service\ProcessFlowHistoryService();
        $service->deleteProcessFlowHistory($this->id);
    }
}
