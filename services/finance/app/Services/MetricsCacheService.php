<?php
namespace App\Services;
use App\Models\CampaignMetricDaily;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MetricsCacheService
{
    public function getDaily(string $campaignId, Carbon $from, Carbon $to): array
    {
        $key = "metrics:daily:$campaignId:{$from->toDateString()}:{$to->toDateString()}";
        return Cache::remember($key, now()->addMinutes(config('metrics.cache_ttl', 5)), function () use ($campaignId, $from, $to) {
            return CampaignMetricDaily::query()
                ->where('campaign_id', $campaignId)
                ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
                ->orderBy('date')
                ->get()
                ->toArray();
        });
    }
}
