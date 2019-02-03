<?php

namespace App\Http\Controllers;

use App\Contracts\IUrlShortener;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function shortUrl(IUrlShortener $shortener)
    {
        $url = $this->getUrlFromRequest();

        return response()->json($shortener->getShortUrl($url));
    }

    private function getUrlFromRequest(): string
    {
        return $this->request->json('url');
    }
}
