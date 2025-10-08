<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignMetricDaily extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id','date','cpa','ctr','conversion_rate','revenue_per_conversion',
        'total_conversions','total_clicks','total_impressions','updated_at'];

    protected $casts = [
        'date'=>'date',
        'updated_at'=>'datetime'
    ];
}
