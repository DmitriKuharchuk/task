<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\DI\Container;
use App\Services\FinanceForwarder;
use App\Repositories\ClickRepository;

class ForwardController extends Controller
{
	public function __construct(private Container $c) {}

	public function forward(Request $r): JsonResponse
	{
		$date = $r->validate(['date'=>'required|date'])['date'];
		$forwarder = $this->c->get(FinanceForwarder::class);
		$repo = $this->c->get(ClickRepository::class);
		$res = $forwarder->forwardDay($repo, $date);
		return response()->json(['date'=>$date] + $res);
	}
}
