<?php


namespace App\Remote;

use App\Models\Portfolio;
use App\Models\Stock;
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
            $gak = round($transactionsForStock->sum('closing_rate') / count($transactionsForStock), 2);
            $service_fees = round($transactionsForStock->sum('service_fee'), 2);
            $price = $profile->price;
            $stock_current_value = round($price * $volume, 2);
            $ps_profit = round(($price - $gak) * $volume, 2);

            $stock = new Stock;
            $stock->symbol = $profile->symbol;
            $stock->volume_of_shares = $volume;
            $stock->ps_avg_price_purchased = $gak;
            $stock->service_fees = $service_fees;
            $stock->stock_current_value = $stock_current_value;
            $stock->ps_profit = $ps_profit;
            $stock->ps_profit_percentage = round(($ps_profit / ($gak * $volume)) * 100, 2);
            $stock->stock_invested = ($volume * $gak) - $service_fees;
            $stock->stock_weight = round(($stock_current_value / $totalPortfolioValue) * 100, 2);

            return $stock;
        })->reject(function ($stock) { return empty($stock); });

        $this->saveStocks($stocks);

        return $stocks;
    }

    private function saveStocks($stocks)
    {
        $portfolioId = Portfolio::firstWhere('user_id', Auth::id())->id;
        $array = json_decode(json_encode($stocks), true);

        foreach ($array as $stock) {
            Stock::updateOrCreate(
                ['portfolio_id' => $portfolioId, 'symbol' => $stock['symbol']],
                $stock
            );
        }
    }
}
