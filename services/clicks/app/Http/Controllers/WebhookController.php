<?php
namespace App\Http\Controllers;

use App\Http\Requests\Click\ClickWebhookRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\DI\Container;
use App\Services\ClickIngestService;

class WebhookController extends Controller
{
	public function __construct(private Container $service) {}

	public function store(ClickWebhookRequest $request): JsonResponse
	{
		$raw = $request->getContent();
		$data = $request->validated();

		$service = $this->service->get(ClickIngestService::class);
		$service->ingest($data, $raw, $request->ip(), $request->userAgent());

		return response()->json(['status'=>'ok']);
	}
}
