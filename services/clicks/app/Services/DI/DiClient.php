<?php
namespace App\Services\DI;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DiClient
{
	public function __construct(private string $baseUrl) {}

	public static function make(): self
	{
		return new self(rtrim(env('DI_BASE_URL', ''), '/'));
	}

	public function getSourceSecret(string $source): ?string
	{
		if (!$this->baseUrl) return null;
		$key = "di:secret:{$source}";
		return Cache::remember($key, now()->addMinutes(10), function () use ($source) {
			$resp = Http::get("{$this->baseUrl}/api/v1/config/secrets", ['source'=>$source]);
			if (!$resp->ok()) return null;
			return $resp->json('secret');
		});
	}

	public function getFinanceConfig(): array
	{
		if (!$this->baseUrl) return [];
		return Cache::remember('di:finance', now()->addMinutes(10), function () {
			$resp = Http::get("{$this->baseUrl}/api/v1/config/finance");
			if (!$resp->ok()) return [];
			return $resp->json() ?? [];
		});
	}
}
