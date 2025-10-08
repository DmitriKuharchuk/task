<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
	protected $fillable = [
		'click_id','offer_id','source','occurred_at','signature','ip','user_agent','raw_json','received_at'
	];

	protected $casts = [
		'occurred_at' => 'datetime',
		'received_at' => 'datetime',
		'raw_json' => 'array'
	];
}
