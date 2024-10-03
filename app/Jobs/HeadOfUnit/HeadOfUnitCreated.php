<?php

namespace App\Jobs\HeadOfUnit;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Skillz\Nnpcreusable\Service\HeadOfUnitService;

class HeadOfUnitCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public array $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function handle(): void
    {
        $service = new HeadOfUnitService();
        $data = new Request($this->data);
        $service->create($data);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
