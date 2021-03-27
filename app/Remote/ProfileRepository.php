<?php


namespace App\Remote;

use App\Models\Currency;
use App\Models\Portfolio;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Ramsey\Collection\Collection;

/*
 * fetch remote profile data with 24 hours of caching in the database
 */
class ProfileRepository
{
    const CACHE_TIME = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;
    const BASE_URL = "https://financialmodelingprep.com/api/v3/";

    function fetchProfiles($symbols)
    {
        if (empty($symbols)) return null;

        $profilesQuery = Profile::whereIn('symbol', $symbols);
        $validSymbols = $profilesQuery->pluck('symbol')->toArray();
        $nonExistingProfiles = array_values(array_diff($symbols, $validSymbols));

        $staleProfiles = $profilesQuery->get()->filter(function ($profile) {
            return $this->isCacheStale($profile);
        })->pluck('symbol')->toArray();

        $symbolsToBeFetched = array_unique(array_merge($staleProfiles, $nonExistingProfiles));

        // fetch and store
        $profiles = $this->getRemoteProfiles($symbolsToBeFetched);
        foreach($profiles as $profile) {
            $matcher = ['symbol' => $profile['symbol']];
            Profile::updateOrCreate($matcher, $profile);
        }

        return $profilesQuery->get()->fresh();
    }

    private function isCacheStale($profile): bool
    {
        return $profile->updated_at->diffInSeconds(Carbon::now()) > $this::CACHE_TIME ||
            $profile->created_at->eq($profile->updated_at);
    }

    private function getRemoteProfiles($symbols): array
    {
        if (empty($symbols)) return array();

        $fSymbols = implode(',', $symbols);
        return json_decode(Http::withOptions([
            'debug' => false
        ])->get($this::BASE_URL . "profile/$fSymbols", [
            'apikey' => env('AV_KEY')
        ])->getBody(), true);
    }
}
