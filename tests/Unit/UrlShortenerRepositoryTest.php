<?php

namespace Tests\Unit;

use App\Contracts\IUrlShortener;
use App\Services\UrlShortenerDataService;
use App\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlShortenerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var UrlShortenerDataService
     */
    private $shortenerDataService;

    /**
     * @var IUrlShortener
     */
    private $urlShortener;

    protected function setUp()
    {
        parent::setUp();
        $this->shortenerDataService = $this->app->make(UrlShortenerDataService::class);
        $this->urlShortener = $this->app->make(IUrlShortener::class);
    }

    public function testFindInDataBaseByUrl_returnNullWhenNotFound()
    {
        $shortUrl = $this->shortenerDataService->findInDataBaseByUrl('http://someurl.com');

        self::assertNull($shortUrl);
    }

    public function testFindInDataBaseByUrl_returnShortUrlModelWhenFound()
    {
        $this->getShoUrlEntity();

        $shortUrl = $this->shortenerDataService->findInDataBaseByUrl('http://someurl.com');

        self::assertNotNull($shortUrl);
    }

    public function testIsInUse_returnTrueWhenAHashIsFound()
    {
        $this->getShoUrlEntity();

        $found = $this->shortenerDataService->isInUse('1234567');

        self::assertTrue($found);
    }

    public function testIsInUse_returnFalseWhenAHashIsNotFound()
    {
        $found = $this->shortenerDataService->isInUse('1234567');

        self::assertFalse($found);
    }

    public function testGetShortUrlAndIncreaseViewsCount_returnNullWhenNotFound()
    {
        $shortUrl = $this->shortenerDataService->getShortUrlAndIncreaseViewsCount('http://someurl.com');

        self::assertNull($shortUrl);
    }

    public function testGetShortUrlAndIncreaseViewsCount_returnShortUrlWhenFound()
    {
        $this->getShoUrlEntity();

        $shortUrl = $this->shortenerDataService->getShortUrlAndIncreaseViewsCount('http://someurl.com');

        self::assertNotNull($shortUrl);
    }

    public function testGetShortUrlAndIncreaseViewsCount_increaseVisitedCount()
    {
        $shortUrlPrevious = $this->getShoUrlEntity();

        $shortUrl = $this->shortenerDataService->getShortUrlAndIncreaseViewsCount('http://someurl.com');

        self::assertEquals(0, $shortUrlPrevious->visit_count);
        self::assertEquals(1, $shortUrl->visit_count);
    }

    public function testGetTopMoreVisitedUrls_first100ShortUrlButOnlyTenHasBeenVisited()
    {
        $shortUrlsDTO = $this->generateUrls('http://someurl.com/', 200);
        $this->randomlyVisitFirstNUrls($shortUrlsDTO, 10, 10);

        $shortUrls = $this->shortenerDataService->getTopMoreVisitedUrls(100);

        self::assertCount(100, $shortUrls);
        self::assertGreaterThan(0, $shortUrls[0]->visit_count);
        self::assertEquals(0, $shortUrls[10]->visit_count);
    }

    public function testGetTopMoreVisitedUrls_first100ShortUrlInDescOrder()
    {
        $shortUrlsDTO = $this->generateUrls('http://someurl.com/', 200);
        $this->randomlyVisitFirstNUrls($shortUrlsDTO, 100, 200);

        $shortUrls = $this->shortenerDataService->getTopMoreVisitedUrls(100);

        self::assertCount(100, $shortUrls);
        self::assertGreaterThan(1, $shortUrls[0]->visit_count);
        self::assertGreaterThan($shortUrls[99]->visit_count, $shortUrls[0]->visit_count);
    }

    private function getShoUrlEntity(): ShortUrl
    {
        $entity = new ShortUrl();
        $entity->long_url = 'http://someurl.com';
        $entity->hash = '1234567';
        $entity->save();
        return $entity;
    }

    private function generateUrls(string $baseUrl, int $amount): array
    {
        $shortUrls = [];
        for ($i = 0; $i < $amount; $i++) {
            $shortUrls[] = $this->urlShortener->getShortUrl($baseUrl . $i);
        }
        return $shortUrls;
    }

    private function randomlyVisitFirstNUrls(array $shortUrlDTO, int $n, int $times)
    {
        for ($i = 0; $i < $times; $i++) {
            $index = mt_rand(0, $n - 1);
            DB::table('short_urls')->where('id', $shortUrlDTO[$index]->id)->increment('visit_count');
        }
    }
}
