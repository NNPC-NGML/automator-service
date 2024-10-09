<?php

namespace App\Jobs\AutomatorTask;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Service\AutomatorTaskService;

class AutomatorProcessFlowFrequency implements ShouldQueue
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

        switch ($this->data["frequency_for"]) {
            case "customers":
                (new AutomatorTaskService())->handleFrequencyForCustomer($this->data);
                break;
            case "suppliers":
                (new AutomatorTaskService())->handleFrequencyForSuppliers($this->data);
                break;
        }
    }
}
