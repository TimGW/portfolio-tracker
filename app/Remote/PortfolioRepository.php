<?php


namespace App\Remote;

use App\Models\Currency;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;

class PortfolioRepository
{
    private $stocks;
    private $totalPortfolioValue;

    public function __construct($stocks, $totalPortfolioValue)
    {
        $this->stocks = $stocks;
        $this->totalPortfolioValue = $totalPortfolioValue;
    }

    function calculatePortfolioIndicators(): Portfolio
    {
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

        $matcher = ['user_id' => Auth::id()];
        return Portfolio::updateOrCreate($matcher, [
            'total_invested' => $portfolio->total_invested,
            'total_profit' => $portfolio->total_profit,
            'total_growth' => $portfolio->total_growth,
            'total_current_value' => $portfolio->total_current_value,
        ]);
    }
}
