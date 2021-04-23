<?php


namespace App\Remote;

use App\Models\Portfolio;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StockRepository
{

    public function buildStocks($allTransactions, $totalPortfolioValue): Collection
    {
        $stocks = $allTransactions->map(function ($transactionsForStock) use ($totalPortfolioValue) {
            $volume = $transactionsForStock->sum('quantity');
            if ($volume === 0) return null; // skip fully sold stocks

            $profile = $transactionsForStock[0]->firstProfile();
            $gak = $transactionsForStock->sum('closing_rate') / count($transactionsForStock);
            $service_fees = $transactionsForStock->sum('service_fee');
            $price = $profile->price;
            $stock_current_value = $price * $volume;
            $ps_profit = (($price - $gak) * $volume) - $service_fees;

            $stock = new Stock;
            $stock->symbol = $profile->symbol;
            $stock->volume_of_shares = $volume;
            $stock->ps_avg_price_purchased = $gak;
            $stock->service_fees = $service_fees;
            $stock->stock_current_value = $stock_current_value;
            $stock->ps_profit = $ps_profit;
            $stock->ps_profit_percentage = ($ps_profit / ($gak * $volume)) * 100;
            $stock->stock_invested = ($volume * $gak) + $service_fees;
            $stock->stock_weight = ($stock_current_value / $totalPortfolioValue) * 100;

            return $stock;
        })->reject(function ($stock) { return empty($stock); });

        $this->saveStocks($stocks);

        return $stocks;
    }

    private function saveStocks($stocks)
    {
        $portfolio = Portfolio::firstOrCreate(['user_id' => Auth::id()]);
        $array = json_decode(json_encode($stocks), true);

        foreach ($array as $stock) {
            Stock::updateOrCreate(
                ['portfolio_id' => $portfolio->id, 'symbol' => $stock['symbol']],
                $stock
            );
        }
    }
}
