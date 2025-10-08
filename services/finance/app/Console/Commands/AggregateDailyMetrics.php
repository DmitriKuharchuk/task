<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Services\AggregationService;
use Carbon\Carbon;

class AggregateDailyMetrics extends Command
{
    protected $signature = 'metrics:aggregate-daily {--date=} {--campaign=}';
    public function handle(AggregationService $svc): int
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date'))->toDateString() : now()->toDateString();
        $campaignId = $this->option('campaign');
        $svc->aggregateDay($date, $campaignId);
        return self::SUCCESS;
    }
}
