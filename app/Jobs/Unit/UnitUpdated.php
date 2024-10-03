<?php

namespace App\Jobs\Unit;

use Skillz\Nnpcreusable\Service\UnitService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UnitUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for updating the unit.
     *
     * @var array
     */
    private array $data;

    /**
     * The ID of the unit to be updated.
     *
     * @var int
     */
    private int $id;

    /**
     * Create a new job instance.
     *
     * @param array $data
     * @param int $id
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->id = $data['id'];
    }

    /**
     * Get the data for updating the unit.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the ID of the unit to be updated.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
        $service->updateUnit($this->data, $this->id);
    }
}
