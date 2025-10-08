<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPerformanceEvent extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id','event_type','event_time','value','currency','metadata','created_at'];

    protected $casts = [
        'metadata'=>'array',
        'event_time'=>'datetime',
        'created_at'=>'datetime'
    ];
}
