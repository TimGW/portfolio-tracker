<?php


namespace App\Remote;

use App\Models\Portfolio;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class StockRepository
{

    public function calculateStockIndicators($stocks, $profiles, $totalPortfolioValue): array
    {
        $stocks = array_map(function ($stock) use ($profiles, $totalPortfolioValue) {
            $volume = $stock->volume_of_shares;
            $gak = $stock->ps_avg_price_purchased;
            $price = $stock->ps_current_value;

            // calculate indicators
            $stock->stock_current_value = round($price * $volume, 2);
            $stock->ps_profit = round(($price - $gak) * $volume, 2);
            $stock->ps_profit_percentage = round(($stock->ps_profit / ($gak * $volume)) * 100, 2);
            $stock->stock_invested = ($volume * $gak) - $stock->service_fees;
            $stock->stock_weight = round(($stock->stock_current_value / $totalPortfolioValue) * 100, 2);

            // add profile info to stocklist
            foreach ($profiles as $profile) {
                if ($stock->symbol == $profile->symbol) {
                    $stock->stock_name = $profile->companyName;
                    $stock->stock_sector = $profile->sector;
                    $stock->ps_current_value = $profile->price;
                    $stock->image = $profile->image;
                }
            }

            return $stock;
        }, $stocks);

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
