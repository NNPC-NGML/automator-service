<?php

namespace App\Jobs\Designation;

// use App\Models\Designation;
use Illuminate\Bus\Queueable;
use Skillz\Nnpcreusable\Service\DesignationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DesignationCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for creating the designation.
     *
     * @var array
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @param array $data The data for creating the designation
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            // Instantiate the DesignationService to create the designation
            $service = new DesignationService();
            $service->createDesignation($this->data);
        } catch (\Exception $e) {
            // Log any errors that occur during the processing of the job
            Log::error('Error occurred while processing DesignationCreated job: ' . $e->getMessage());
        }
    }
}
