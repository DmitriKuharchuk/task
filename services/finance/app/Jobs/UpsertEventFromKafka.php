<?php
namespace App\Jobs;
use App\Models\CampaignPerformanceEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;

class UpsertEventFromKafka implements ShouldQueue
{
    use Dispatchable, Queueable;
    public function __construct(private array $payload) {}
    public function handle(): void
    {
        CampaignPerformanceEvent::updateOrCreate(
            ['id' => $this->payload['id']],
            [
                'campaign_id' => $this->payload['campaign_id'],
                'event_type'  => $this->payload['event_type'],
                'event_time'  => Carbon::parse($this->payload['event_time']),
                'value'       => $this->payload['value'] ?? null,
                'currency'    => $this->payload['currency'] ?? null,
                'metadata'    => $this->payload['metadata'] ?? [],
                'created_at'  => now(),
            ]
        );
    }
}
