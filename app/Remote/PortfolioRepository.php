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
    private const BASEURL = "https://financialmodelingprep.com/api/v3/";
    private $allTransactions;
    private $symbolRepository;
    private $currencyRepository;

    public function __construct($allTransactions)
    {
        $this->allTransactions = $allTransactions;
        $this->symbolRepository = new SymbolRepository($allTransactions);
        $this->currencyRepository = new CurrencyRepository;
    }

    public function buildPortfolio(): Portfolio
    {
        $stock_data_set = $this->fetchProfileData();

        $total_portfolio_value = $stock_data_set->sum(function ($stock) {
            return round($stock->ps_current_value * $stock->volume_of_shares, 2);
        });
        $stock_list = $this->calculateStockIndicators($stock_data_set, $total_portfolio_value);
        return $this->calculatePortfolioIndicators($stock_list, $total_portfolio_value);
    }

    private function fetchProfileData(): Collection
    {
        $result = Portfolio::where('user_id', Auth::id())->firstOr(function () {
            return $this->getRemoteProfiles();
        });

        $secondsInDay = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;
        if ($result->updated_at->diffInSeconds(Carbon::now()) > $secondsInDay) {
            $profiles = $this->getRemoteProfiles();
        } else {
            $profiles = $result;
        }

        return $this->convertToEur($profiles->stocks);
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

            $this->saveFinancialData($stock);
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
        ])->get($this::BASEURL . "profile/$formatted_tickers", [
            'apikey' => env('AV_KEY')
        ]);

        $result = json_decode($response->getBody(), true); // todo don't use array

        foreach ($result as $profile) {
            $this->saveProfileData($profile);
        }

        return $result;
    }

    private function saveProfileData($stock_profile)
    {
        Stock::where('isin', $stock_profile['isin'])
            ->where('portfolio_id', Auth::id())
            ->update([
                    'stock_name' => $stock_profile['companyName'],
                    'stock_sector' => $stock_profile['sector'],
                    'ps_current_value' => $stock_profile['price'],
                    'image' => $stock_profile['image']
                ]
            );
    }

    private function saveFinancialData($stock)
    {
        Stock::where('isin', $stock->isin)
            ->where('portfolio_id', Auth::id())
            ->update([
                'ps_profit' => $stock->ps_profit,
                'ps_profit_percentage' => $stock->ps_profit_percentage,
                'stock_current_value' => $stock->stock_current_value,
                'stock_weight' => $stock->stock_weight,
                'stock_invested' => $stock->stock_invested
            ]);
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
