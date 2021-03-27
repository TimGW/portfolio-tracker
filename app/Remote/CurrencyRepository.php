<?php


namespace App\Remote;

use App\Models\Currency;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CurrencyRepository
{
    const CACHE_TIME = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;
    const EUR = "EUR";

    function convertToEur($currency, $amount)
    {
        if (strcasecmp($currency, $this::EUR)) {
            $symbol = $currency . $this::EUR;
        } else {
            return $amount;
        }

        $conversionPrice = $this->fetchCurrency(explode(",", $symbol))->first()->price;
        return $conversionPrice * $amount;
    }

    function fetchCurrency($symbols)
    {
        if (empty($symbols)) return null;

        $currencyQuery = Currency::wherein('symbol', $symbols);
        $validSymbols = $currencyQuery->pluck('symbol')->toArray();
        $nonExistingProfiles = array_values(array_diff($symbols, $validSymbols));

        $staleData = $currencyQuery->get()->filter(function ($profile) {
            return $this->isCacheStale($profile);
        })->pluck('symbol')->toArray();

        $symbolsToBeFetched = array_unique(array_merge($staleData, $nonExistingProfiles));

        // fetch and store
        $currencies = $this->getRemoteCurrencies($symbolsToBeFetched);
        foreach($currencies as $profile) {
            $matcher = ['symbol' => $profile['symbol']];
            Currency::updateOrCreate($matcher, $profile);
        }

        return $currencyQuery->get()->fresh();
    }

    private function isCacheStale($data): bool
    {
        return $data->updated_at->diffInSeconds(Carbon::now()) > $this::CACHE_TIME ||
            $data->created_at->eq($data->updated_at);
    }

    private function getRemoteCurrencies($symbols): array
    {
        if (empty($symbols)) return array();

        $fSymbols = implode(',', $symbols);
        return json_decode(Http::withOptions([
            'debug' => false
        ])->get("https://financialmodelingprep.com/api/v3/quote/" . $fSymbols, [
            'apikey' => env('AV_KEY')
        ])->getBody(), true);
    }
}
