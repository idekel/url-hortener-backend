<?php

namespace App\Services;

use App\Contracts\IUrlHash;
use App\Contracts\IUrlShortener;
use App\Exceptions\InvalidUrlException;
use App\ShortUrlDTO;

class UrlShortener implements IUrlShortener
{
    /**
     * @var IUrlHash
     */
    private $hashService;

    /**
     * @var UrlShortenerDataService
     */
    private $urlDataService;

    public function __construct(IUrlHash $hashService, UrlShortenerDataService $finder)
    {
        $this->hashService = $hashService;
        $this->urlDataService = $finder;
    }

    function getShortUrl(string $url): ShortUrlDTO
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException();
        }
        $shortUrl = $this->urlDataService->findInDataBaseByUrl($url);
        if (!$shortUrl) {

            $shortUrl = $this->registerNewUrl($url);
        }
        return new ShortUrlDTO($shortUrl);
    }

    private function registerNewUrl(string $longUrl)
    {
        return $this->urlDataService->storeWithHash($longUrl, function () use ($longUrl) {
            return $this->getNextHash($longUrl);
        });
    }

    public function getNextHash(string $longUrl, string $hash = null): string
    {
        if (!$hash) {
            $hash = $this->hashService->getUrlHash($longUrl);
        }
        while ($this->urlDataService->isInUse($hash)) {
            $hash = $this->hashService->getUrlHash($longUrl);
        }
        return $hash;
    }
}
