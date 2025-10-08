<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Kafka\Consumer;
use App\Services\ClickIngestService;

class KafkaClicksConsumer extends Command
{
	protected $signature = 'clicks:kafka-consume';
	protected $description = 'Consume clicks from Kafka and persist to DB';

	public function handle(ClickIngestService $svc): int
	{
		$consumer = Consumer::make();
		foreach ($consumer->stream() as $msg) {
			$payload = $msg['payload'] ?? null;
			$raw = $msg['raw'] ?? json_encode($payload ?? []);
			if ($payload) {
				$svc->ingest($payload, $raw, $msg['ip'] ?? null, $msg['ua'] ?? null);
			}
		}
		return self::SUCCESS;
	}
}
