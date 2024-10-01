<?php

namespace App\Jobs\User;

use Skillz\Nnpcreusable\Service\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserDeleted implements ShouldQueue
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
        $service = new  UserService();
        $service->deleteUser($this->id);
    }

    public function getId(): int
    {
        return $this->id;
    }
}
