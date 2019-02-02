<?php

namespace Tests\Feature;

use App\Contracts\IUrlShortener;
use App\Exceptions\InvalidUrlException;
use App\ShortUrl;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var IUrlShortener
     */
    private $urlShortener;

    protected function setUp()
    {
        parent::setUp();
        $this->urlShortener = $this->app->make(IUrlShortener::class);
    }

    public function testGetShortUrl_createNewShortUrl()
    {
        $shortUrl = $this->urlShortener->getShortUrl('http://test.io/154224');

        self::assertEquals(1, $shortUrl->id);
        self::assertEquals(1, ShortUrl::count());
    }

    public function testGetShortUrl_returnStoredShortUrl()
    {
        $this->urlShortener->getShortUrl('http://test.io/154224');
        $shortUrl = $this->urlShortener->getShortUrl('http://test.io/154224');

        self::assertEquals(1, $shortUrl->id);
        self::assertEquals(1, ShortUrl::count());
    }

    public function testGetShortUrl_storeNewShortUrls()
    {
        $this->urlShortener->getShortUrl('http://test.io/154224');
        $this->urlShortener->getShortUrl('http://test.io/154221');
        $this->urlShortener->getShortUrl('http://test.io/154222');
        $this->urlShortener->getShortUrl('http://verydiferenturl.io/154221');


        self::assertEquals(4, ShortUrl::count());
    }

    public function testGetShortUrl_doNotStoreInvalidUrl()
    {
        $this->expectException(InvalidUrlException::class);

        $this->urlShortener->getShortUrl('test.com');
    }

    public function testValidateHash_returnSameHashWhenHashIsNotInUsed()
    {
        $hash = $this->urlShortener->getNextHash('http://somereallylongurl/fkjg8', '1234567');

        self::assertEquals('1234567', $hash);
    }

    public function testValidateHash_returnOtherHashWhenHashIsInUsed()
    {
        $shortUrl = new ShortUrl();
        $shortUrl->hash = '1234567';
        $shortUrl->long_url = 'http://someurl.com';
        $shortUrl->save();

        $hash = $this->urlShortener->getNextHash('http://somereallylongurl/fkjg8', '1234567');

        self::assertNotEquals('1234567', $hash);
    }
}
