<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class UrlParserResource extends JsonResource{
  public function toArray($r): array { return $this->resource; }
}
