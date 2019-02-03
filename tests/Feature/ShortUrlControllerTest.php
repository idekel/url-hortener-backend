<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShortUrl_statusCodeOk()
    {
        $response = $this->call('PUT',
            'api/short/url',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            json_encode(['url' => 'http://php.net/manual/en/class.closure.php']));

        $response->assertStatus(200);
    }

    public function testShortUrl_statusCodeServerErrorBecauseOfInavalidUrl()
    {
        $response = $this->call('PUT',
            'api/short/url',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            json_encode(['url' => '//php.net/manual/en/class.closure.php']));

        $response->assertStatus(500);
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }

    public function testShortUrl_optionsHttpVerbStatusOk()
    {
        $response = $this->call('OPTIONS',
            'api/short/url',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            json_encode(['url' => 'http://php.net/manual/en/class.closure.php']));

        $response->assertStatus(200);
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }
}
