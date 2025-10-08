<?php
namespace App\Services;

class KafkaProducer {
	public function __construct(private string $bootstrap, private string $topic) {}

	public function publish(array $message): void
	{
		error_log('[KAFKA] topic='.$this->topic.' payload='.json_encode($message, JSON_UNESCAPED_UNICODE));
	}
}
