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

        $conversionPrice = $this->fetchCurrency($symbol)->first()->price;
        return $conversionPrice * $amount;
    }

    function fetchCurrency($symbol)
    {
        if (empty($symbol)) return null;

        $currencyQuery = Currency::where('symbol', $symbol);
        $result = $currencyQuery->firstOr(function () use ($symbol) {
            return $this->getRemoteAndCache($symbol);
        });

        if ($this->isCacheStale($result)) $this->getRemoteAndCache($symbol);

        return $currencyQuery->get()->fresh();
    }

    private function getRemoteAndCache($symbol)
    {
        $response = $this->getRemoteCurrency($symbol);
        $matcher = ['symbol' => $response['symbol']];
        return Currency::updateOrCreate($matcher, $response);
    }

    private function isCacheStale($data): bool
    {
        return $data->updated_at->diffInSeconds(Carbon::now()) > $this::CACHE_TIME ||
            $data->created_at->eq($data->updated_at);
    }

    private function getRemoteCurrency($symbol): array
    {
        if (empty($symbol)) return array();

        return json_decode(Http::withOptions([
            'debug' => false
        ])->get("https://financialmodelingprep.com/api/v3/quote/" . $symbol, [
            'apikey' => env('AV_KEY')
        ])->getBody(), true)[0];
    }
}
