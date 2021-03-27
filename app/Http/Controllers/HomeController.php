<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use App\Models\Portfolio;
use App\Remote\PortfolioRepository;
use App\Remote\ProfileRepository;
use App\Remote\StockRepository;
use App\Remote\SymbolRepository;
use App\Remote\TransactionRepository;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactionRepository = new TransactionRepository();
        $transactions = $transactionRepository->getGroupedTransactionsForUser();

        if (empty($transactions->all())) {
            return view('empty');
        }

        $portfolio = $this->buildPortfolioForTransactions($transactions);
        $chart = new Chart($portfolio->stocks);

        return view('home', compact('portfolio', 'chart'));
    }

    private function buildPortfolioForTransactions($transactions): Portfolio
    {
        $symbolRepository = new SymbolRepository($transactions);
        $stocks = $symbolRepository->getStocksWithSymbols();

        $profileRepository = new ProfileRepository;
        $profiles = $profileRepository->fetchProfiles(array_column($stocks, 'symbol'));

        $stocks = $this->addCurrentValueToStocks($stocks, $profiles);
        $totalPortfolioValue = $this->calculateTotalPortfolioValue($stocks);

        $stockRepository = new StockRepository;
        $stockList = $stockRepository->calculateStockIndicators($stocks, $profiles, $totalPortfolioValue);

        $portfolioRepository = new PortfolioRepository($stockList, $totalPortfolioValue);
        return $portfolioRepository->calculatePortfolioIndicators();
    }

    private function addCurrentValueToStocks($stocks, $profiles)
    {
        foreach ($stocks as $key => $stock) {
            foreach ($profiles as $profile) {
                if ($stock->symbol == $profile->symbol) {
                    $stocks[$key]->ps_current_value = $profile->price;
                }
            }
        }
        return $stocks;
    }

    private function calculateTotalPortfolioValue($stocks)
    {
        return collect($stocks)->sum(function ($stock) {
            return round($stock->ps_current_value * $stock->volume_of_shares, 2);
        });
    }
}
