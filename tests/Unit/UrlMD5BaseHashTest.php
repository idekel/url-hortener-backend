<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UrlMD5BaseHash;

class UrlMD5BaseHashTest extends TestCase
{
    /**
     * @var UrlMD5BaseHash
     */
    private $urlShorterner;

    protected function setUp()
    {
        parent::setUp();
        $this->urlShorterner = new UrlMD5BaseHash();
    }

    public function testGetShortUrl_urlHashIsOnySevenCharactersLong()
    {
        $hash = $this->urlShorterner->getUrlHash($this->getLongUrlSample());

        self::assertEquals(7, strlen($hash));
    }

    public function testGetShortUrl_differentHashForSameUrl()
    {
        $hash1 = $this->urlShorterner->getUrlHash($this->getLongUrlSample());
        $hash2 = $this->urlShorterner->getUrlHash($this->getLongUrlSample());

        self::assertNotEquals($hash1, $hash2);
    }


    private function getLongUrlSample(): string
    {
        return 'https://www.google.com.do/search?q=really+long+url&btnK=Google+Search&oq=really+long+url&gs_l';
    }
}
