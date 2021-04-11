<?php


namespace App\Remote;

use App\Models\Metrics;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

/*
 * fetch remote metrics data with 24 hours of caching in the database
 */
class MetricsRepository
{
    const CACHE_TIME = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;
    const BASE_URL = "https://financialmodelingprep.com/api/v3/";

    function fetchMetrics($symbols)
    {
        if (empty($symbols)) return null;

        $metricsQuery = Metrics::whereIn('symbol', $symbols);
        $validSymbols = $metricsQuery->pluck('symbol')->toArray();
        $nonExistingMetrics = array_values(array_diff($symbols, $validSymbols));

        $staleMetricss = $metricsQuery->get()->filter(function ($metric) {
            return $this->isCacheStale($metric);
        })->pluck('symbol')->toArray();

        $symbolsToBeFetched = array_unique(array_merge($staleMetricss, $nonExistingMetrics));

        // fetch and store
        $metrics = $this->getRemoteMetrics($symbolsToBeFetched);
        foreach($metrics as $metric) {
            $matcher = ['symbol' => $metric['symbol']];
            Metrics::updateOrCreate($matcher, $metric);
        }
    }

    private function isCacheStale($metric): bool
    {
        return $metric->updated_at->diffInSeconds(Carbon::now()) > $this::CACHE_TIME ||
            $metric->created_at->eq($metric->updated_at);
    }

    private function getRemoteMetrics($symbols): array
    {
        if (empty($symbols)) return array();

        $fSymbols = implode(',', $symbols);
        return json_decode(Http::withOptions([
            'debug' => false
        ])->get($this::BASE_URL . "key-metrics/$fSymbols", [
            'limit' => 10,
            'apikey' => env('AV_KEY')
        ])->getBody(), true);
    }
}
