<?php

namespace App\Http\Controllers;

use App\Services\UrlShortenerDataService;
use App\ShortUrlDTO;

class ShortUrlStatsController extends Controller
{
    /**
     * @var UrlShortenerDataService
     */
    private $dataService;

    public function __construct(UrlShortenerDataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function getTopVisitedShortUrls(int $top)
    {
        $urlDTOS = [];
        $shortUrls = $this->dataService->getTopMoreVisitedUrls($top);
        if (count($shortUrls) > 0) {
            $urlDTOS = $shortUrls->map(function ($shortUrl) {
                return new ShortUrlDTO($shortUrl);
            }, $shortUrls->toArray());
        }
        return response()->json($urlDTOS);
    }
}
