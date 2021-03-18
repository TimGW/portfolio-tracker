<?php


namespace App\Remote;

use App\Models\Portfolio;
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

    public function buildPortfolio(): Portfolio
    {
        // retrieve stock info and update stock database
        $stock_profile = $this->getStockProfiles();
        foreach ($stock_profile as $profile) {
            $this->saveProfileData($profile);
        }

        // calculate all portfolio indicators for seperate stocks
        $pair = $this->buildStockList();
        $stock_list = $pair[0];

        // calculate all global portfolio indicators
        $total_invested = array_sum(array_column($stock_list, 'stock_invested'));
        $total_profit = array_sum(array_column($stock_list, 'ps_profit'));
        $total_growth = round(($total_profit / $total_invested) * 100, 2);
        $total_current_value = $pair[1];

        return new Portfolio($stock_list, $total_invested, $total_profit, $total_growth, $total_current_value);
    }

    private function buildStockList(): array
    {
        $total_portfolio_value = 0;
        $stock_list = Stock::where('user_id', Auth::id())->get()->toArray();

        foreach ($stock_list as $key => $stock) {
            $volume = $stock['volume_of_shares'];
            $gak = $stock['ps_avg_price_purchased'];
            $price = $stock['ps_current_value'];

            $stock_list[$key]['ps_profit'] = round(($price - $gak) * $volume, 2);
            $stock_list[$key]['ps_profit_percentage'] = round(($stock_list[$key]['ps_profit'] / ($gak * $volume)) * 100, 2);
            $stock_list[$key]['stock_current_value'] = round($price * $volume, 2);
            $stock_list[$key]['stock_invested'] = $volume * $gak;
            $total_portfolio_value += $stock_list[$key]['stock_current_value'];

            $this->saveFinancialData($stock);
        }

        foreach ($stock_list as $key => $stock) {
            $stock_list[$key]['stock_weight'] = round(($stock_list[$key]['stock_current_value'] / $total_portfolio_value) * 100);

            $this->saveFinancialData($stock);
        }

        return array($stock_list, $total_portfolio_value);
    }

    private function getStockProfiles()
    {
        $stock_tickers = $this->symbolRepository->getTickerList();
        $formatted_tickers = implode(',', array_filter($stock_tickers));

        $response = Http::withOptions([
            'debug' => false
        ])->get($this->baseUrl . "profile/$formatted_tickers", [
            'apikey' => env('AV_KEY')
        ]);

        return json_decode($response->getBody(), true);
    }

    private function saveProfileData($stock_profile)
    {
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

        Stock::where('stock_ticker', null)->delete();
    }

    private function saveFinancialData($stock)
    {
        Stock::where('isin', $stock['isin'])
            ->where('user_id', Auth::id())
            ->update([
                'ps_profit' => $stock['ps_profit'],
                'ps_profit_percentage' => $stock['ps_profit_percentage'],
                'stock_current_value' => $stock['stock_current_value'],
                'stock_weight' => $stock['stock_weight'],
                'stock_invested' => $stock['stock_invested']
            ]);
    }
}
