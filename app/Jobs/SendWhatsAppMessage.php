<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [60, 300, 600, 1800, 3600];

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $target,
        protected string $message
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $service): void
    {
        $result = $service->sendMessage($this->target, $this->message);

        // If the service logic returns false or an error status, we want to fail the job so it retries
        // Note: WhatsAppService::sendMessage handles Log::error and returns $result array or false.
        // Fonnte returns ['status' => true/false, 'reason' => '...']
        
        if ($result === false || (isset($result['status']) && $result['status'] === false)) {
            $reason = is_array($result) ? ($result['reason'] ?? 'Unknown error') : 'Service returned false';
            
            Log::warning("WhatsApp Job failing for {$this->target}. Reason: {$reason}. Attempt: {$this->attempts()}");
            
            // Throw exception to trigger retry
            throw new \Exception("WhatsApp delivery failed: " . $reason);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("WhatsApp Job permanently failed for {$this->target} after {$this->tries} attempts. Error: " . $exception->getMessage());
    }
}
