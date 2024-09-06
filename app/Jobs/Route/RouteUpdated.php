<?php

namespace App\Jobs\Route;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RouteUpdated implements ShouldQueue
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
        $service = new  \Skillz\Nnpcreusable\Service\RouteService();
        $data = new \Illuminate\Http\Request($this->data);
        $service->updateRoute($this->id, $data);
    }
}
