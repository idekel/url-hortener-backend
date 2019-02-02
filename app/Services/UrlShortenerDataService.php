<?php

namespace App\Services;


use App\ShortUrl;
use Illuminate\Support\Facades\DB;

class UrlShortenerDataService
{
    public function findInDataBaseByUrl(string $longUrl): ?ShortUrl
    {
        return ShortUrl::query()->where('long_url', $longUrl)->first();
    }

    public function isInUse(string $hash): bool
    {
        return ShortUrl::query()->where('hash', $hash)->count() > 0;
    }


    public function getShortUrlAndIncreaseViewsCount(string $longUrl): ?ShortUrl
    {
        $shortUrl = $this->findInDataBaseByUrl($longUrl);
        if ($shortUrl) {
            //delegate this the database to be safe
            DB::table($shortUrl->getTable())->where('id', $shortUrl->id)->increment('visit_count');
            //visit_count could be diferent at this point but is not so important
            //we could also do return ShortUrl::find($id); but that would hurt performance
            $shortUrl->visit_count++;
            return $shortUrl;
        }
        return $shortUrl;
    }


    /**
     * @param int $top
     * @return ShortUrl[]
     */
    public function getTopMoreVisitedUrls(int $top)
    {
        return ShortUrl::query()
            ->orderBy('visit_count', 'desc')
            ->limit($top)
            ->get();
    }


    public function storeWithHash($longUrl, \Closure $hashGen): ShortUrl
    {
        $shortUrl = new ShortUrl();
        $shortUrl->long_url = $longUrl;
        DB::transaction(function () use ($shortUrl, $hashGen) {
            $shortUrl->hash = $hashGen();
            $shortUrl->save();
        });
        return $shortUrl;
    }
}
