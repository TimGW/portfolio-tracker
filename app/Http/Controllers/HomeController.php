<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Chart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Imports\StockDataMapper;
use App\Imports\ChartBuilder;

class HomeController extends Controller
{
    private $stockDataMapper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->stockDataMapper = new StockDataMapper;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactions = $this->fetchItemsForUserGroupedByColumn("transactions", "isin");

        $result = array();
        foreach ($transactions as $key => $subarray) {
            $sumOfQuantity = array_sum(array_column($subarray,'quantity'));
            $gak = array_sum(array_column($subarray,'closing_rate')) / $sumOfQuantity;
            $result[$key] = $gak;
        }

        $quotes = $this->getStockQuotes($transactions);
        $chart = new Chart($transactions);

        return view('home', compact('transactions', 'chart', 'quotes'));
    }

    private function getTickerSymbols($transactions)
    {
        $isins = array_column($transactions, 'symbol_isin');

        $requestBody = array();
        foreach ($isins as $isin) {
            if (str_contains($isin, "NL")){
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$isin", 'exchCode' => 'NA');
            } elseif (str_contains($isin, "US")) {
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$isin", 'exchCode' => 'US');
            } else {
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$isin");
            }
        };

        $json = json_encode($requestBody);

        $tickerResponse = Http::withOptions([
            'debug' => false
        ])->withHeaders([
            'Content-Type' => 'text/json',
            'X-OPENFIGI-APIKEY' => env('OPEN_FIGI_KEY'),
        ])->withBody(
            $json,
            'text/json'
        )->post("https://api.openfigi.com/v1/mapping");

        $tickerResult = $tickerResponse->getBody();
        $tickerResult = json_decode($tickerResult, true);
        $tickers = array();

        $dataTickers = array_column($tickerResult, 'data');
        foreach ($dataTickers as $item) {
            $first = $item[0];

            if (str_contains($first['exchCode'], "NA")){
                $tickers[] = $first['ticker'] . ".AS";
            } else {
                $tickers[] = $first['ticker'];
            }
        }

        return $tickers;
    }

    public function getStockQuotes($transactions)
    {
        $baseUrl = "https://financialmodelingprep.com/api/v3/";

        $tickerSymbols = $this->getTickerSymbols($transactions);
        $tickersCsv = implode(',', $tickerSymbols);

        $response = Http::withOptions([
            'debug' => false
        ])->get($baseUrl . "quote/$tickersCsv", [
            'apikey' => env('AV_KEY')
        ]);
        $result = $response->getBody();
        $result = json_decode($result);

        return $result;
    }

    function array_group(array $data, $by_column): array
    {
        $result = array();
        foreach ($data as $element) {
            $result[$element[$by_column]][] = $element;
        }
        return $result;
    }

    private function fetchItemsForUserGroupedByColumn($table, $column): array
    {
        $transactions = DB::table($table)
            ->where('user_id', '=', Auth::id())
            ->get()
            ->toArray();

        $transactionsArray = array_map(function ($value) {
            return (array)$value;
        }, $transactions);

        return $this->array_group($transactionsArray, $column);
    }
}
