<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CampaignPerformanceEvent;
use App\Models\Campaign;
class CampaignPerformanceEventFactory extends Factory
{
    protected $model = CampaignPerformanceEvent::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'event_type' => $this->faker->word(),
            'event_time' => $this->faker->word(),
            'value' => $this->faker->word(),
            'currency' => 'USD',
            'metadata' => [],
        ];
    }
}
