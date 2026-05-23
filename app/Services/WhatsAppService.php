<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected bool $enabled;
    protected string $baseUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
        $this->enabled = config('services.fonnte.enabled', false);
    }

    /**
     * Send a WhatsApp message via Fonnte
     * 
     * @param string $target Phone number (e.g., 08973816530 or 628973816530)
     * @param string $message The message content
     * @return array|bool
     */
    public function sendMessage(string $target, string $message)
    {
        // Normalize phone number (convert leading 0 to 62)
        if (str_starts_with($target, '0')) {
            $target = '62' . substr($target, 1);
        }
        if (!$this->enabled) {
            Log::info("WhatsApp Notification disabled. Target: {$target}, Message: {$message}");
            return true;
        }

        if (empty($this->token)) {
            Log::error("WhatsApp Notification failed: Fonnte token is missing.");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl, [
                'target' => $target,
                'message' => $message,
                'delay' => '2', // Optional: delay in seconds
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? false)) {
                Log::info("WhatsApp Notification sent successfully to {$target}.");
                return $result;
            }

            Log::error("WhatsApp Notification failed to {$target}. Response: " . json_encode($result));
            return $result;
        } catch (\Exception $e) {
            Log::error("WhatsApp Notification Exception: " . $e->getMessage());
            return false;
        }
    }
}
