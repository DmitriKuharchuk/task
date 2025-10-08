<?php
namespace App\Services\Kafka;

use Illuminate\Support\Facades\Log;

class Consumer
{
	public function __construct(private string $brokers, private string $groupId, private string $topic) {}

	public static function make(): self
	{
		return new self(config('kafka.brokers'), config('kafka.group_id'), config('kafka.topic_clicks'));
	}

	/** @return iterable<array> */
	public function stream(): iterable
	{
		// Заглушка: эмулируем пустой поток.
		Log::info('[Kafka] consumer stream start', ['topic' => $this->topic]);
		if (false) { yield []; }
	}
}
