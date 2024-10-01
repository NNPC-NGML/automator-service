<?php

namespace App\Jobs\Unit;

use Skillz\Nnpcreusable\Service\UnitService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UnitCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for creating the unit.
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

    /**
     * Execute the job.
     *
     * @param UnitService $service
     * @return void
     */
    public function handle(): void
    {
        $service = new  UnitService();
        $service->createUnit($this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
