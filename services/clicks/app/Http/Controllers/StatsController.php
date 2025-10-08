<?php
namespace App\Http\Controllers;

use App\Http\Requests\Click\ClickStatRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\DI\Container;
use App\Services\ClickStatsService;

class StatsController extends Controller
{
	public function __construct(private Container $service) {}

	public function period(ClickStatRequest $request): JsonResponse
	{
		$data = $request->validated();

		$svc = $this->service->get(ClickStatsService::class);
		$items = $svc->period($data['from'], $data['to'], $data['sort'] ?? 'count', $data['order'] ?? 'desc', $data['limit'] ?? 100);

		return response()->json(['from'=>$data['from'],'to'=>$data['to'],'items'=>$items]);
	}
}
