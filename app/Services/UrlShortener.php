<?php

namespace App\Services;

use App\Contracts\IUrlShortener;
use App\Contracts\IUrlHash;
use App\Exceptions\InvalidUrlException;
use App\ShortUrl;
use App\ShortUrlDTO;

class UrlShortener implements IUrlShortener
{
    private $hashService;

    public function __construct(IUrlHash $hashService)
    {
        $this->hashService = $hashService;
    }

    function getShortUrl(string $url): ShortUrlDTO
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException();
        }
        $shortUrl = $this->findInDataBase($url);
        if (!$shortUrl) {

            $shortUrl = $this->registerNewUrl($url);
        }
        return new ShortUrlDTO($shortUrl);
    }

    private function findInDataBase(string $longUrl): ?ShortUrl
    {
        return ShortUrl::query()->where('long_url', $longUrl)->first();
    }

    private function registerNewUrl(string $longUrl)
    {
        $shortUrl = new ShortUrl();
        $shortUrl->long_url = $longUrl;
        $shortUrl->hash = $this->getNextHash($longUrl);
        $shortUrl->save();
        return $shortUrl;
    }

    public function getNextHash(string $longUrl, string $hash = null): string
    {
        if (!$hash) {
            $hash = $this->hashService->getUrlHash($longUrl);
        }
        while ($this->isInUse($hash)) {
            $hash = $this->hashService->getUrlHash($longUrl);
        }
        return $hash;
    }

    private function isInUse(string $hash): bool
    {
        return ShortUrl::query()->where('hash', $hash)->count() > 0;
    }
}
