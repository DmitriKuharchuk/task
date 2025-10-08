<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Repositories\ClickRepository;

final class FinanceForwarder
{
    public function __construct(
        private string $endpoint,
        private int $batchSize = 5000,
        private int $timeout = 10
    ){}

    public function forwardDay(ClickRepository $repo, string $date): array
    {
        $result = ['batches'=>0,'sent'=>0];
        $repo->listByDate($date, $this->batchSize, function (array $chunk) use (&$result, $date) {
            $payload = ['date' => $date, 'items' => $chunk];
            $resp = Http::timeout($this->timeout)->post(rtrim($this->endpoint,'/').'/api/v1/ingest/clicks', $payload);
            if (!$resp->ok()) {
                throw new \RuntimeException("Finance forward failed: ".$resp->status());
            }
            $result['batches']++;
            $result['sent'] += count($chunk);
        });
        return $result;
    }
}
