<?php

namespace App\Jobs\User;


use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Skillz\Nnpcreusable\Service\UserService;

class UserCreated implements ShouldQueue
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
     * @param UserService $service
     * @return void
     */
    public function handle(): void
    {
        Log::info('Handling UserCreated job with data: ', ['data' => $this->data]);

        $service = new  UserService();
        $data = new Request($this->data);
        $service->createUser($data);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
