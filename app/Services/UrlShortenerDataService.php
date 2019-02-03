<?php

namespace App\Services;


use App\ShortUrl;
use Illuminate\Support\Facades\DB;

class UrlShortenerDataService
{
    public function findInDataBaseByUrl(string $longUrl): ?ShortUrl
    {
        return $this->findByColumnValue('long_url', $longUrl);
    }

    public function isInUse(string $hash): bool
    {
        return ShortUrl::query()->where('hash', $hash)->count() > 0;
    }


    public function getShortUrlAndIncreaseViewsCount(string $hash): ?ShortUrl
    {
        $shortUrl = $this->findByHash($hash);
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

    private function findByHash(string $hash): ?ShortUrl
    {
        return $this->findByColumnValue('hash', $hash);
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

    /**
     * @param string $column
     * @param mixed $longUrl
     * @return ShortUrl
     */
    private function findByColumnValue(string $column, $longUrl)
    {
        return ShortUrl::query()->where($column, $longUrl)->first();
    }
}
