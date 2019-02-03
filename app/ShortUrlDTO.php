<?php

namespace App;


class ShortUrlDTO
{

    /**
     * @var string
     */
    public $hashUrl;

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $viewCount;

    /**
     * @var string
     */
    public $longUrl;

    public function __construct(ShortUrl $shortUrl)
    {
        $this->id = $shortUrl->id;
        $this->hashUrl = route('hash', $shortUrl->hash);
        $this->viewCount = $shortUrl->visit_count;
        $this->longUrl = $shortUrl->long_url;
    }
}
