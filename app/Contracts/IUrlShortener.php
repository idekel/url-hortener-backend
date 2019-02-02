<?php

namespace App\Contracts;

use App\ShortUrlDTO;

interface IUrlShortener
{
    function getShortUrl(string $url): ShortUrlDTO;

    function getNextHash(string $longUrl, string $hash = null): string;
}
