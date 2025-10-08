<?php

namespace App\Http\Controllers\Parser;

use App\Http\Controllers\Controller;
use App\Http\Requests\UrlParserRequest;
use App\Http\Resources\UrlParserResource;
use App\Services\UrlParserService;
use Illuminate\Http\Request;

class ParserController extends Controller
{

    public function __construct(private UrlParserService $service)
    {
    }

    public function parse(UrlParserRequest $request) : UrlParserResource
    {
        $data = $request->validated();
        return new UrlParserResource($this->service->parse($data['url']));
    }

}
