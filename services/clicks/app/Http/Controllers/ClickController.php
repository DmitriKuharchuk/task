<?php
namespace App\Http\Controllers;

use App\Http\Requests\ClickRequest;
use App\Http\Resources\ClickResource;
use App\Services\ClickService;
use App\Services\SignatureVerifier;
use App\Services\KafkaProducer;

class ClickController extends Controller
{
    private ClickService $svc;

    public function __construct()
    {
	    $this->svc = new ClickService(
	        new SignatureVerifier(env('CLICKS_SIGNATURE_SECRET','')),
	        new KafkaProducer(env('KAFKA_BOOTSTRAP','kafka:9092'), env('KAFKA_TOPIC_CLICKS_INGESTED','clicks.ingested'))
	    );
    }

    public function ingest(ClickRequest $request)
    {
	    $click = $this->svc->ingest($request->validated());
	    return (new ClickResource($click))->response();
    }

    public function stats()
    {
	    $data = request()->validate(['from'=>'required|date','to'=>'required|date','sort'=>'sometimes|string','order'=>'sometimes|in:asc,desc']);
	    return response()->json($this->svc->stats($data['from'],$data['to'],$data['sort'] ?? 'day',$data['order'] ?? 'asc'));
    }

    public function forward()
    {
	    $date = request()->validate(['date'=>'required|date'])['date'];
	    return response()->json(['forwarded'=>$this->svc->forwardDay($date),'date'=>$date]);
    }
}
