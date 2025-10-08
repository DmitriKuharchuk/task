<?php
namespace App\Services;

use App\Repositories\ClickRepository;
use Illuminate\Support\Facades\Queue;
use App\Jobs\PersistClickJob;

final class ClickIngestService
{
    public function __construct(
        private SignatureVerifier $verifier,
        private ClickRepository $repo,
        private bool $useQueue = false
    ){}

    public function ingest(array $payload, string $rawJson, ?string $ip, ?string $ua): void
    {
        if (!$this->verifier->verify($payload['source'] ?? '', $rawJson, $payload['signature'] ?? null)) {
            throw new \RuntimeException('Invalid signature');
        }
        if ($this->useQueue) {
            Queue::push(new PersistClickJob($payload, $ip, $ua));
        } else {
            $this->repo->upsert($payload, $ip, $ua);
        }
    }
}
