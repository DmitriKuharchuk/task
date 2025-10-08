<?php

namespace App\Kafka\Consumers;

use App\Jobs\UpsertEventFromKafka;

class CampaignEventsConsumer
{
    public function __invoke(array $message): void
    {
        UpsertEventFromKafka::dispatchSync($message);
    }
}
