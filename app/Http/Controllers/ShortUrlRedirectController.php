<?php

namespace App\Http\Controllers;

use App\Services\UrlShortenerDataService;
use Illuminate\Http\Request;

class ShortUrlRedirectController extends Controller
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var UrlShortenerDataService
     */
    private $dataService;

    public function __construct(UrlShortenerDataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
        $this->request = $request;
    }

    public function goTo(string $hash)
    {
        $shortUrl = $this->dataService->getShortUrlAndIncreaseViewsCount($hash);
        if (!$shortUrl) {
            abort(404);
        }
        $this->dataService->logShortUrlVisit($shortUrl, $this->request);
        return redirect($shortUrl->long_url);
    }
}
