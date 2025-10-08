<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CampaignMetricDaily;
use App\Models\Campaign;
class CampaignMetricDailyFactory extends Factory
{
    protected $model = CampaignMetricDaily::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'cpa' => $this->faker->randomFloat(2, 0, 10000),
            'ctr' => $this->faker->randomFloat(2, 0, 10000),
            'conversion_rate' => $this->faker->randomFloat(2, 0, 10000),
            'revenue_per_conversion' => $this->faker->randomFloat(2, 0, 10000),
            'total_conversions' => $this->faker->randomFloat(2, 0, 10000),
            'total_clicks' => $this->faker->randomFloat(2, 0, 10000),
            'total_impressions' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}
