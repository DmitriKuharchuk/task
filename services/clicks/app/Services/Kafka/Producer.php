<?php
namespace App\Services\Kafka;

use Illuminate\Support\Facades\Log;

class Producer
{
	public function __construct(private string $brokers, private string $topic) {}

	public static function make(): self
	{
		return new self(config('kafka.brokers'), config('kafka.topic_clicks'));
	}

	public function publish(string $key, array $payload): void
	{
		Log::info('[Kafka] publish', ['topic' => $this->topic, 'key' => $key, 'payload' => $payload]);
	}
}
