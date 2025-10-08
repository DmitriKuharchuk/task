<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Campaign;
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'ad_group_id' => $this->faker->word(),
            'campaign_type' => $this->faker->word(),
            'status' => $this->faker->word(),
        ];
    }
}
