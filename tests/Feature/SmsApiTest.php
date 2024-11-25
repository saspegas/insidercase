<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Message;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmsApiTest extends TestCase
{
    public function test_message_is_processed(): void
    {
        Queue::fake();

        $smsServiceMock = Mockery::mock(\App\Services\Interfaces\SendSmsServiceInterface::class);
        $this->app->instance(\App\Services\Interfaces\SendSmsServiceInterface::class, $smsServiceMock);

        $message = Message::factory()->create();

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'status' => 'new',
        ]);

        $this->artisan('app:add-messages-to-queue');

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'status' => 'queued',
        ]);

        $smsServiceMock->shouldReceive('send')
            ->once()
            ->with($message->recipient, $message->text)
            ->andReturn('MOCKED_CUSTOM_MESSAGE_ID');

        Queue::push(new \App\Jobs\SendSms(
            $smsServiceMock,
            $message->id,
            $message->recipient,
            $message->text,
        ));

        Queue::assertPushed(\App\Jobs\SendSms::class);

        (new \App\Jobs\SendSms(
            $smsServiceMock,
            $message->id,
            $message->recipient,
            $message->text
        ))->handle();

        $this->assertEquals('MOCKED_CUSTOM_MESSAGE_ID', Redis::get("sms:{$message->id}:message_id"));
        $this->assertNotNull(Redis::get("sms:{$message->id}:sent_at"));

        $smsServiceMock->shouldReceive('send')
            ->once()
            ->with($message->recipient, $message->text)
            ->andReturn(false);

        (new \App\Jobs\SendSms(
            $smsServiceMock,
            $message->id,
            $message->recipient,
            $message->text
        ))->handle();

        $this->assertEquals('Failed to send SMS', Redis::get("sms:{$message->id}:error"));

        $this->assertDatabaseMissing('messages', [
            'id' => $message->id,
            'status' => 'new',
        ]);
    }
}
