<?php

namespace App\Jobs\LocationAssignment;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Skillz\Nnpcreusable\Models\Location;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LocationUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function handle(): void
    {
        $model = Location::where(["id" => $this->data["id"]])->first();
        if ($model) {
            $model->update($this->data);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }
}
