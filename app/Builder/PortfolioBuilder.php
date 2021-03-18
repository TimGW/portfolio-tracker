<?php

namespace App\Builder;


use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class PortfolioBuilder
{
    public $stock_list;

    public $total_current_value;
    public $total_growth;
    public $total_invested;
    public $total_profit;

    public function __construct()
    {
        $this->stock_list = Stock::where('user_id', Auth::id())->get()->toArray();
    }

    public function build()
    {
        $total_portfolio_value = 0;

        foreach ($this->stock_list as $stock) {
            $price = $stock['ps_current_value'];
            $volume = $stock['volume_of_shares'];
            $gak = $stock['ps_avg_price_purchased'];

            $stock['ps_profit'] = round(($price - $gak) * $volume, 2);
            $stock['ps_profit_percentage'] = round(($stock['ps_profit'] / ($gak * $volume)) * 100, 2);
            $stock['stock_current_value'] = round($price * $volume, 2);
            $stock['stock_invested'] = $volume * $gak;
            $total_portfolio_value = array_sum(array_column($this->stock_list, 'stock_current_value'));
            $stock['stock_weight'] = round(($stock['stock_current_value'] / $total_portfolio_value) * 100);

            $this->save($stock);
        }

        $this->total_invested = array_sum(array_column($this->stock_list, 'stock_invested'));
        $this->total_profit = array_sum(array_column($this->stock_list, 'ps_profit'));
        $this->total_growth = round(($this->total_profit / $this->total_invested) * 100, 2);
        $this->total_current_value = $total_portfolio_value;
    }

    private function save($stock)
    {
        Stock::where('isin', $stock['isin'])
            ->where('user_id', Auth::id())
            ->update([
                'ps_profit' => $stock['ps_profit'],
                'ps_profit_percentage' => $stock['ps_profit_percentage'],
                'stock_current_value' => $stock['stock_current_value'],
                'stock_weight' => $stock['stock_weight'],
                'stock_invested' => $stock['stock_invested'],
            ]);
    }
}
