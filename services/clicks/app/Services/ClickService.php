<?php
namespace App\Services;

use App\Models\Click;
use Illuminate\Support\Facades\DB;

class ClickService
{
  public function __construct(
	private SignatureVerifier $verifier,
	private KafkaProducer $producer
  ) {}

  public function ingest(array $payload): Click
  {
	if (!$this->verifier->verify($payload, $payload['signature'] ?? '')) {
	  throw new \InvalidArgumentException('Invalid signature');
	}
	$attrs = [
	  'click_id'=>$payload['click_id'],
	  'offer_id'=>(int)$payload['offer_id'],
	  'source'=>$payload['source'],
	  'ts_utc'=>$payload['timestamp'],
	  'user_ip'=>$payload['ip']??null,
	  'ua'=>$payload['ua']??null,
	  'url'=>$payload['url']??null,
	  'signature_ok'=>true,
	];
	$click = Click::updateOrCreate(['click_id'=>$payload['click_id']], $attrs);

	$this->producer->publish(['type'=>'click.ingested','data'=>$attrs,'at'=>date('c')]);
	return $click;
  }

  public function stats(string $from, string $to, string $sort='day', string $order='asc')
  {
	$q = DB::table('fact_clicks')
	  ->whereBetween('ts_utc', [$from,$to])
	  ->selectRaw("date_trunc('day', ts_utc) as day, offer_id, source, count(*) as clicks")
	  ->groupBy('day','offer_id','source')
	  ->orderBy($sort, $order);

	return $q->limit(1000)->get();
  }

  public function forwardDay(string $date): int
  {
	$n=0;
	Click::whereDate('ts_utc', $date)->orderBy('click_id')->chunk(1000, function($batch) use (&$n){
	  foreach($batch as $row){
		$this->producer->publish([
		  'type'=>'click.export',
		  'data'=>[
			'click_id'=>$row->click_id,
			'offer_id'=>(int)$row->offer_id,
			'source'=>$row->source,
			'ts_utc'=>$row->ts_utc,
			'ip'=>$row->user_ip,
			'ua'=>$row->ua,
			'url'=>$row->url,
		  ],
		  'at'=>date('c'),
		]);
		$n++;
	  }
	});
	return $n;
  }
}
