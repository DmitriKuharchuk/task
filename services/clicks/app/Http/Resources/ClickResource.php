<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ClickResource extends JsonResource{

  public function toArray($request): array
  {
	return [
        'click_id'=>$this->click_id,
        'offer_id'=>(int)$this->offer_id,
        'source'=>$this->source,
        'timestamp'=>$this->ts_utc,
        'ip'=>$this->user_ip,
        'ua'=>$this->ua,
        'url'=>$this->url,
	];
  }

}
