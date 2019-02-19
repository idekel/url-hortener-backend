<?php

namespace Tests\Feature;

use App\ShortUrl;
use App\ShortUrlLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlRedirectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGoTo_statusNotFound()
    {
        $response = $this->get('g/1234567');

        $response->assertStatus(404);
    }

    public function testGoTo_status302()
    {
        $shorUrl = new ShortUrl();
        $shorUrl->hash = '1234567';
        $shorUrl->long_url = 'http://someurl.com';
        $shorUrl->save();

        $response = $this->get('g/1234567');

        $response->assertStatus(302);
    }

    public function testGoTo_IncreaseVisitedCount()
    {
        $shorUrl = new ShortUrl();
        $shorUrl->hash = '1234567';
        $shorUrl->long_url = 'http://someurl.com';
        $shorUrl->save();

        $this->get('g/1234567');

        self::assertEquals(0, $shorUrl->visit_count);
        self::assertEquals(1, ShortUrl::find(1)->visit_count);
    }

    public function testGoTo_LogUrlVisit()
    {
        $shorUrl = new ShortUrl();
        $shorUrl->hash = '1234567';
        $shorUrl->long_url = 'http://someurl.com';
        $shorUrl->save();

        $this->get('g/1234567');

        self::assertEquals(1, ShortUrlLog::count());
    }
}
