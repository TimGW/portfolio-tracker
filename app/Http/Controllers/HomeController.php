<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Ticker;
use Illuminate\Support\Facades\Auth;
use App\Models\Chart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allTransactions = $this->fetchItemsForUserGroupedByColumn("transactions", "isin");
        $stocks = array();
        $totalPortfolioValue = 0;

        $profiles = $this->getStockProfiles($allTransactions);

        foreach ($allTransactions as $transactionsForShare) {
            foreach ($profiles as $profile) {
                if ($transactionsForShare[0]['isin'] === $profile['isin']) {
                    $stock = new Stock($transactionsForShare, $profile['price']);
                    $totalPortfolioValue += $stock->current_stock_value;
                    $stocks[] = $stock;
                }
            }
        }

        foreach ($stocks as $stock) {
            $stock->setWeight($totalPortfolioValue);
        }

        $chart = new Chart($stocks);

        return view('home', compact('stocks', 'chart'));
    }

    private function createTickers($allTransactions): array
    {
        $localTickers = array();
        foreach ($allTransactions as $data) {
            $localTickers[] = new Ticker($data[0]['isin'], $data[0]['exchange']);
        }

        $requestBody = array();
        foreach ($localTickers as $ticker) {
            $exchCode = $ticker->bb_exchange;
            if (!empty($exchCode)) {
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$ticker->isin", 'exchCode' => $exchCode);
            } else {
                $requestBody[] = array('idType' => "ID_ISIN", 'idValue' => "$ticker->isin");
            }
        }

        $tickerResponse = Http::withOptions([
            'debug' => false
        ])->withHeaders([
            'Content-Type' => 'text/json',
            'X-OPENFIGI-APIKEY' => env('OPEN_FIGI_KEY'),
        ])->withBody(
            json_encode($requestBody),
            'text/json'
        )->post("https://api.openfigi.com/v1/mapping");

        $responseBody = $tickerResponse->getBody();
        $responseDataSet = array_column(json_decode($responseBody, true), 'data');
        for ($x = 0; $x < count($localTickers); $x++) {
            $objectData = $responseDataSet[$x][0];

            $localTicker = $localTickers[$x];
            if (!$localTicker->isCommonStock($objectData['securityType'])) {
                $appendix = $localTickers[$x]->exch_appendix;
                if (!empty($appendix)) {
                    $localTicker->ticker = $objectData['ticker'] . "." . $appendix;
                } else {
                    $localTicker->ticker = $objectData['ticker'];
                }
            }
        }

        return array_column($localTickers, 'ticker');
    }

    public function getStockProfiles($transactions)
    {
        $baseUrl = "https://financialmodelingprep.com/api/v3/";

        $tickers = implode(',', array_filter($this->createTickers($transactions)));
        $response = Http::withOptions([
            'debug' => false
        ])->get($baseUrl . "profile/$tickers", [
            'apikey' => env('AV_KEY')
        ]);

        return json_decode($response->getBody(), true);
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
