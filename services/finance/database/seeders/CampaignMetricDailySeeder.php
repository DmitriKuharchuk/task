<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CampaignMetricDaily;

class CampaignMetricDailySeeder extends Seeder
{
    public function run(): void
    {
        CampaignMetricDaily::factory()->count(1000)->create();
    }
}
