<?php

namespace App\Http\Controllers;

use App\Services\UrlShortenerDataService;

class ShortUrlRedirectController extends Controller
{

    /**
     * @var UrlShortenerDataService
     */
    private $dataService;

    public function __construct(UrlShortenerDataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function goTo(string $hash)
    {
        $shortUrl = $this->dataService->getShortUrlAndIncreaseViewsCount($hash);
        if (!$shortUrl) {
            abort(404);
        }
        return redirect($shortUrl->long_url);
    }
}
