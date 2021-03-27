<?php


namespace App\Remote;

use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;

class PortfolioRepository
{
    private $stocks;
    private $currencyRepository;
    private $totalPortfolioValue;

    public function __construct($stocks, $totalPortfolioValue)
    {
        $this->stocks = $stocks;
        $this->totalPortfolioValue = $totalPortfolioValue;
        $this->currencyRepository = new CurrencyRepository;
    }

    function calculatePortfolioIndicators(): Portfolio
    {
        $this->currencyRepository->convertToEur('USD', 5);
        $totalPortfolioValue = $this->totalPortfolioValue;
        $stocks = collect($this->stocks);

        $total_invested = $stocks->sum('stock_invested');
        $total_profit = $stocks->sum('ps_profit');

        $portfolio = new Portfolio;
        $portfolio->stocks = $stocks;
        $portfolio->total_invested = $total_invested;
        $portfolio->total_profit = $total_profit;
        $portfolio->total_growth = round(($total_profit / $total_invested) * 100, 2);
        $portfolio->total_current_value = $totalPortfolioValue;

        return Portfolio::firstWhere('user_id', Auth::id())->updateOrCreate([
                'total_invested' => $portfolio->total_invested,
                'total_profit' => $portfolio->total_profit,
                'total_growth' => $portfolio->total_growth,
                'total_current_value' => $portfolio->total_current_value,
            ]
        );
    }
//
//    private function convertToEur($data_set): Collection
//    {
//        $decimals = 2;
//        foreach ($data_set as $key => $data) {
//            $data_set[$key]->ps_avg_price_purchased =
//                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->ps_avg_price_purchased), $decimals);
//            $data_set[$key]->ps_current_value =
//                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->ps_current_value), $decimals);
//            $data_set[$key]->ps_profit =
//                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->ps_profit), $decimals);
//            $data_set[$key]->ps_profit_percentage =
//                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->ps_profit_percentage), $decimals);
//            $data_set[$key]->stock_current_value =
//                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->stock_current_value), $decimals);
//            $data_set[$key]->stock_invested =
//                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->stock_invested), $decimals);
//        }
//
//        return $data_set;
//    }
}
