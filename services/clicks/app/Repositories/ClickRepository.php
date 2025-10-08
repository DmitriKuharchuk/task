<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClickRepository
{
    public function upsert(array $payload, string $ip = null, string $ua = null): void
    {
        DB::statement('
            INSERT INTO clicks (click_id, offer_id, source, occurred_at, signature, ip, user_agent, raw_json, received_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ON CONFLICT (click_id) DO NOTHING
        ', [
            $payload['click_id'],
            (int) $payload['offer_id'],
            $payload['source'],
            $payload['timestamp'],
            $payload['signature'] ?? null,
            $ip,
            $ua,
            json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
        ]);
    }

    public function stats(Carbon $from, Carbon $to, string $sort = 'count', string $order = 'desc', ?int $limit = 100)
    {
        $order = strtolower($order) === 'asc' ? 'asc' : 'desc';
        $sortAllowed = ['count','offer_id','source'];
        $sort = in_array($sort, $sortAllowed, true) ? $sort : 'count';

        $query = DB::table('clicks')
            ->selectRaw('offer_id, source, COUNT(*)::int as count')
            ->whereBetween('occurred_at', [$from, $to])
            ->groupBy('offer_id','source');

        if ($sort === 'offer_id' || $sort === 'source') {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('count', $order);
        }

        if ($limit) $query->limit($limit);
        return $query->get()->toArray();
    }

    public function listByDate(string $date, int $chunk, callable $callback): void
    {
        $start = Carbon::parse($date)->startOfDay();
        $end   = Carbon::parse($date)->endOfDay();

        DB::table('clicks')
            ->select('click_id','offer_id','source','occurred_at')
            ->whereBetween('occurred_at', [$start, $end])
            ->orderBy('occurred_at')
            ->chunk($chunk, function ($rows) use ($callback) {
                $payload = [];
                foreach ($rows as $r) {
                    $payload[] = [
                        'click_id' => $r->click_id,
                        'offer_id' => (int)$r->offer_id,
                        'source' => $r->source,
                        'timestamp' => (string) $r->occurred_at,
                    ];
                }
                $callback($payload);
            });
    }
}
