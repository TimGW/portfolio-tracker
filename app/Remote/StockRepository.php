<?php


namespace App\Remote;


use Illuminate\Support\Facades\Http;

class StockRepository
{
    private $allTransactions;
    private $tickerRepository;

    public function __construct($allTransactions)
    {
        $this->allTransactions = $allTransactions;
        $this->tickerRepository = new TickerRepository($allTransactions);
    }

    public function getStockProfiles()
    {
        $baseUrl = "https://financialmodelingprep.com/api/v3/";

        $tickers = $this->tickerRepository->getTickerList();

        $response = Http::withOptions([
            'debug' => false
        ])->get($baseUrl . "profile/$tickers", [
            'apikey' => env('AV_KEY')
        ]);

        return json_decode($response->getBody(), true);
    }

}
