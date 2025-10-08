<?php
namespace App\Http\Controllers;

use App\Services\DI\Container;
use App\Services\LoggerService;
use App\Services\TestService;

class DIExampleController extends Controller
{
    public function index()
    {
        $c = new Container();
        $c->singleton(LoggerService::class, fn() => new LoggerService());
        $c->bind(TestService::class, TestService::class);

        $test = $c->get(TestService::class);
        return response()->json(['ok'=>true,'msg'=>$test->run()]);
    }
}
