<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = ['name','ad_group_id','campaign_type','status'];

    public function events()
    {
        return $this->hasMany(CampaignPerformanceEvent::class);
    }
    public function dailyMetrics()
    {
        return $this->hasMany(CampaignMetricDaily::class);
    }
}
