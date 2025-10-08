<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\DI\Container;
use App\Services\ClickIngestService;
use App\Services\ClickStatsService;
use App\Services\FinanceForwarder;
use App\Services\SignatureVerifier;
use App\Repositories\ClickRepository;

class DiBridgeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Container::class, function () {
            $c = new Container();

            $c->singleton(SignatureVerifier::class, function () {
                return new SignatureVerifier(config('clicks.sources', []));
            });

            $c->singleton(ClickRepository::class, function () {
                return new ClickRepository();
            });

            $c->singleton(ClickIngestService::class, function ($c) {
                return new ClickIngestService(
                    $c->get(SignatureVerifier::class),
                    $c->get(ClickRepository::class),
                    (bool) config('clicks.queue_ingest', false)
                );
            });

            $c->singleton(ClickStatsService::class, function ($c) {
                return new ClickStatsService($c->get(ClickRepository::class));
            });

            $c->singleton(FinanceForwarder::class, function () {
                return new FinanceForwarder(
                    config('finance.endpoint'),
                    (int) config('finance.forward_batch', 5000),
                    (int) config('finance.timeout', 10)
                );
            });

            return $c;
        });
    }
}
