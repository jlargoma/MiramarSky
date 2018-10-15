<?php

namespace App\Repositories;

use App\Prices;
use App\Seasons;
use Illuminate\Support\Facades\Cache;

class BookRepository
{
    const TTL_IN_MINUTES = 5 * 24 * 60;

    /**
     * @param string $date
     * @return mixed
     */
    public function getSeasonType($date)
    {
        return Cache::remember('season_for_' . str_slug($date, '_'), self::TTL_IN_MINUTES, function () use ($date) {
            return Seasons::getSeason($date);
        });
    }

    /**
     * @param string $season
     * @param int $pax
     */
    public function getCostsFromSeason($season, $pax)
    {
        return Cache::remember("prices_season_{$season}_pax_{$pax}", self::TTL_IN_MINUTES, function () use ($season, $pax) {
            return Prices::select('cost')->where('season', $season)
                ->where('occupation', $pax)->get();
        });
    }
}