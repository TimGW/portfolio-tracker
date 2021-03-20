<?php


namespace App\Remote;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CurrencyRepository
{
    function convertCurrencyToEur($currency, $amount)
    {
        $convertTo = "EUR";
        if (strcasecmp($currency, $convertTo)) {
            $convertTo = $currency . $convertTo;
        } else {
            return $amount;
        }

        $now = Carbon::now();
        $secondsInDay = 86400; // 3600 * 24

        $result = Currency::where('symbol', '=', $convertTo)->firstOr(function () use ($convertTo) {
            return $this->getRemoteCurrency($convertTo);
        });

        // return cached result if not older than 1 day
        if ($result->updated_at->diffInSeconds($now) > $secondsInDay) {
            $conversionPrice = $this->getRemoteCurrency($convertTo)->price;
        } else {
            $conversionPrice = $result->price;
        }

        return $conversionPrice * $amount;
    }

    private function getRemoteCurrency($convertTo): Currency
    {
        $response = json_decode(Http::withOptions([
            'debug' => false
        ])->get("https://financialmodelingprep.com/api/v3/quote/" . $convertTo, [
            'apikey' => env('AV_KEY')
        ])->getBody(), true);

        Currency::where('symbol', $response[0]['symbol'])->update(['price' => $response[0]['price']]);

        return $this->updateOrCreate($response[0]);
    }

    private function updateOrCreate($response)
    {
        $currency = Currency::where('symbol', $response['symbol'])->first();

        if ($currency !== null) {
            $currency->update(['price' => $response['price']]);
        } else {
            $currency = Currency::create([
                'symbol' => $response['symbol'],
                'price' => $response['price'],
            ]);
        }

        return $currency;
    }
}
