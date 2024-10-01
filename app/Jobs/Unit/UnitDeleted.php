<?php

namespace App\Jobs\Unit;

use Skillz\Nnpcreusable\Service\UnitService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UnitDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $id;
    public function __construct($id)
    {
        $this->id = $id;
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
        $service->deleteUnit($this->id);
    }

    public function getId(): int
    {
        return $this->id;
    }
}
