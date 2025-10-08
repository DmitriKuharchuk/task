<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CampaignPerformanceEvent;

class CampaignPerformanceEventSeeder extends Seeder
{
    public function run(): void
    {
        CampaignPerformanceEvent::factory()->count(1000)->create();
    }
}
