<?php

namespace App\Contracts;

interface IUrlHash
{
    function getUrlHash(string $longUrl): string;
}
