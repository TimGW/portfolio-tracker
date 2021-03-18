<?php


namespace App\Remote;


use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StockRepository
{
    private $allTransactions;
    private $baseUrl = "https://financialmodelingprep.com/api/v3/";
    private $symbolRepository;

    public function __construct($allTransactions)
    {
        $this->allTransactions = $allTransactions;
        $this->symbolRepository = new SymbolRepository($allTransactions);
    }

    public function getStocks()
    {
        $stock_tickers = $this->symbolRepository->getTickerList();
        $formatted_tickers = implode(',', array_filter($stock_tickers));

        $response = Http::withOptions([
            'debug' => false
        ])->get($this->baseUrl . "profile/$formatted_tickers", [
            'apikey' => env('AV_KEY')
        ]);

        $stocks = json_decode($response->getBody(), true);

        $this->save($stocks);
    }

    private function save(array $stock_profiles)
    {
        foreach ($this->allTransactions as $transaction) {
            foreach ($stock_profiles as $stock_profile) {
                if ($stock_profile['isin'] === $transaction[0]['isin']) {
                    Stock::where('isin', $stock_profile['isin'])
                        ->where('user_id', Auth::id())
                        ->update([
                            'stock_ticker' => $stock_profile['symbol'],
                            'stock_name' => $stock_profile['companyName'],
                            'stock_sector' => $stock_profile['sector'],
                            'currency' => $stock_profile['currency'],
                            'ps_current_value' => $stock_profile['price'],
                            'image' => $stock_profile['image']
                        ]);
                }
            }
        }
        Stock::where('stock_ticker', null)->delete();
    }
}
