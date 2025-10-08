<?php
namespace App\Http\Controllers;
use App\Services\MetricsCacheService;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MetricsController extends Controller
{

    public function __construct(MetricsCacheService $cache)
    {
    }

    public function daily(string $id, Request $request)
    {
        $from = Carbon::parse($request->query('from', now()->subDays(30)->toDateString()));
        $to   = Carbon::parse($request->query('to', now()->toDateString()));
        abort_if(!Campaign::whereKey($id)->exists(), 404, 'Campaign not found');
        return response()->json([
            'campaign_id' => $id,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'items' => $this->cache->getDaily($id, $from, $to),
        ]);
    }
}
