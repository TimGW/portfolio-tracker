<?php


namespace App\Remote;

use App\Models\Portfolio;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PortfolioRepository
{
    const BASE_URL = "https://financialmodelingprep.com/api/v3/";
    const CACHE_TIME = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;
    private $allTransactions;
    private $symbolRepository;
    private $currencyRepository;

    public function __construct($allTransactions)
    {
        $this->allTransactions = $allTransactions;
        $this->symbolRepository = new SymbolRepository($allTransactions);
        $this->currencyRepository = new CurrencyRepository;
    }

    public function getPortfolio(): Portfolio
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->first();

        // check if cache is old or has not yet been fetched for first time
        if ($portfolio->updated_at->diffInSeconds(Carbon::now()) > $this::CACHE_TIME ||
            $portfolio->created_at->eq($portfolio->updated_at)
        ) {
            $profiles = $this->getRemoteProfiles();
            $this->updateStockProfiles($profiles);
        }

        $stocks = $this->convertToEur($portfolio->stocks);
        $total_portfolio_value = $stocks->sum(function ($stock) {
            return round($stock->ps_current_value * $stock->volume_of_shares, 2);
        });

        $stock_list = $this->calculateStockIndicators($stocks, $total_portfolio_value);
        $this->updateStockIndicators($stock_list);

        $portfolio = $this->calculatePortfolioIndicators($stock_list, $total_portfolio_value);
        $this->updatePortfolio($portfolio);

        return Portfolio::where('user_id', Auth::id())->first();
    }

    private function calculatePortfolioIndicators($stock_list, $total_portfolio_value): Portfolio
    {
        $total_invested = $stock_list->sum('stock_invested');
        $total_profit = $stock_list->sum('ps_profit');

        $portfolio = new Portfolio;
        $portfolio->stocks = $stock_list;
        $portfolio->total_invested = $total_invested;
        $portfolio->total_profit = $total_profit;
        $portfolio->total_growth = round(($total_profit / $total_invested) * 100, 2);
        $portfolio->total_current_value = $total_portfolio_value;

        return $portfolio;
    }

    private function calculateStockIndicators($stock_list, $total_portfolio_value): Collection
    {
        foreach ($stock_list as $key => $stock) {
            $volume = $stock->volume_of_shares;
            $gak = $stock->ps_avg_price_purchased;
            $price = $stock->ps_current_value;

            $stock_list[$key]->stock_current_value = round($price * $volume, 2);
            $stock_list[$key]->ps_profit = round(($price - $gak) * $volume, 2);
            $stock_list[$key]->ps_profit_percentage = round(($stock_list[$key]->ps_profit / ($gak * $volume)) * 100, 2);
            $stock_list[$key]->stock_invested = ($volume * $gak) - $stock->service_fees;
            $stock_list[$key]->stock_weight = round(($stock_list[$key]->stock_current_value / $total_portfolio_value) * 100, 2);
        }

        return $stock_list;
    }

    private function getRemoteProfiles()
    {
        $stocks = $this->symbolRepository->getStocksWithSymbols();
        $tickers = array_map(function ($stock) {
            return $stock->stock_ticker;
        }, $stocks);
        $formatted_tickers = implode(',', $tickers);

        $response = Http::withOptions([
            'debug' => false
        ])->get($this::BASE_URL . "profile/$formatted_tickers", [
            'apikey' => env('AV_KEY')
        ]);

        return json_decode($response->getBody());
    }

    private function updatePortfolio($portfolio)
    {
        Portfolio::firstWhere('user_id', Auth::id())->update([
                'total_invested' => $portfolio->total_invested,
                'total_profit' => $portfolio->total_profit,
                'total_growth' => $portfolio->total_growth,
                'total_current_value' => $portfolio->total_current_value,
            ]
        );
    }

    private function updateStockProfiles($profiles)
    {
        foreach ($profiles as $stock_profile) {
            Portfolio::firstWhere('user_id', Auth::id())
                ->stocks()
                ->where('stock_ticker', $stock_profile->symbol)
                ->update([
                        'stock_name' => $stock_profile->companyName,
                        'stock_sector' => $stock_profile->sector,
                        'ps_current_value' => $stock_profile->price,
                        'image' => $stock_profile->image
                    ]
                );
        }
    }

    private function updateStockIndicators($stock_list)
    {
        foreach ($stock_list as $stock) {
            Portfolio::firstWhere('user_id', Auth::id())
                ->stocks()
                ->where('stock_ticker', $stock->stock_ticker)
                ->update([
                    'ps_profit' => $stock->ps_profit,
                    'ps_profit_percentage' => $stock->ps_profit_percentage,
                    'stock_current_value' => $stock->stock_current_value,
                    'stock_weight' => $stock->stock_weight,
                    'stock_invested' => $stock->stock_invested
                ]);
        }
    }

    private function convertToEur($data_set): Collection
    {
        $decimals = 2;
        foreach ($data_set as $key => $data) {
            $data_set[$key]->ps_avg_price_purchased =
                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->ps_avg_price_purchased), $decimals);
            $data_set[$key]->ps_current_value =
                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->ps_current_value), $decimals);
            $data_set[$key]->ps_profit =
                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->ps_profit), $decimals);
            $data_set[$key]->ps_profit_percentage =
                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->ps_profit_percentage), $decimals);
            $data_set[$key]->stock_current_value =
                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->stock_current_value), $decimals);
            $data_set[$key]->stock_invested =
                round($this->currencyRepository->convertCurrencyToEur($data->currency, $data->stock_invested), $decimals);
        }

        return $data_set;
    }
}
