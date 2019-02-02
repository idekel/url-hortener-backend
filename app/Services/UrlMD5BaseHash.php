<?php

namespace App\Services;

use App\Contracts\IUrlHash;

class UrlMD5BaseHash implements IUrlHash
{
    function getUrlHash(string $longUrl): string
    {
        $longUrl = md5($longUrl . mt_rand(1, mt_getrandmax()));
        return substr($longUrl, 0, 7);
    }

}
