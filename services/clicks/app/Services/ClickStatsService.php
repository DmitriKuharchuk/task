<?php
namespace App\Services;

use App\Repositories\ClickRepository;
use Carbon\Carbon;

final class ClickStatsService
{
    public function __construct(private ClickRepository $repo) {}

    public function period(string $from, string $to, string $sort, string $order, ?int $limit = 100): array
    {
        return $this->repo->stats(Carbon::parse($from), Carbon::parse($to), $sort, $order, $limit);
    }
}
