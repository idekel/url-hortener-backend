<?php

namespace App;


class ShortUrlDTO
{
    /**
     * @var string
     */
    public $accessUrl;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $viewCount;

    public function __construct(ShortUrl $shortUrl)
    {
        $this->id = $shortUrl->id;
        $this->hash = $shortUrl->hash;
        $this->viewCount = $shortUrl->visit_count;
        $this->accessUrl = $this->hash;
    }
}
