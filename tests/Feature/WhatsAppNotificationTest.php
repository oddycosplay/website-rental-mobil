<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Config;

class WhatsAppNotificationTest extends TestCase
{
    public function test_whatsapp_service_sends_post_request_to_fonnte()
    {
        // 1. Mock Configuration
        Config::set('services.fonnte.token', 'test-token');
        Config::set('services.fonnte.enabled', true);

        // 2. Mock Http Facade
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        // 3. Trigger Service
        $wa = new WhatsAppService();
        $wa->sendMessage('08973816530', 'Test Message');

        // 4. Assert Http was called correctly
        Http::assertSent(function ($request) {
            return $request->url() == 'https://api.fonnte.com/send' &&
                   $request->header('Authorization')[0] == 'test-token' &&
                   $request['target'] == '08973816530' &&
                   $request['message'] == 'Test Message';
        });
    }

    public function test_whatsapp_service_respects_enabled_flag()
    {
        // 1. Mock Configuration (Disabled)
        Config::set('services.fonnte.enabled', false);

        // 2. Mock Http Facade
        Http::fake();

        // 3. Trigger Service
        $wa = new WhatsAppService();
        $wa->sendMessage('08973816530', 'Test Message');

        // 4. Assert Http was NEVER called
        Http::assertNothingSent();
    }
}
