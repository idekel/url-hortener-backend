<?php

namespace Tests\Feature;

use App\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlStatsControllerTest extends TestCase
{
    use RefreshDatabase;


    public function testGetTopVisitedShortUrls_statusOk()
    {
        $response = $this->get('api/top/100/most/visited/urls');

        $response->assertStatus(200);
    }

    public function testGetTopVisitedShortUrls_noUrls()
    {
        $response = $this->get('api/top/100/most/visited/urls');

        self::assertCount(0, $response->json());
    }

    public function testGetTopVisitedShortUrls_oneUrls()
    {
        $shorUrl = new ShortUrl();
        $shorUrl->long_url = 'http://test.com';
        $shorUrl->hash = '1234567';
        $shorUrl->save();

        $response = $this->get('api/top/100/most/visited/urls');

        self::assertCount(1, $response->json());
    }
}
