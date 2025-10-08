<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\ClickRepository;

class PersistClickJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(private array $payload, private ?string $ip, private ?string $ua) {}

    public function handle(ClickRepository $repo): void
    {
        $repo->upsert($this->payload, $this->ip, $this->ua);
    }
}
