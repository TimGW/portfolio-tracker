<?php


namespace App\Dashboard;


use App\Models\Chart;
use App\Models\Dashboard;
use App\Models\Portfolio;
use App\Remote\PortfolioRepository;
use App\Remote\ProfileRepository;
use App\Remote\StockRepository;
use App\Remote\SymbolRepository;

class DashboardBuilder
{
    private $transactionRepository;
    private $symbolRepository;
    private $profileRepository;
    private $stockRepository;
    private $portfolioRepository;

    public function __construct($transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;

        $this->symbolRepository = new SymbolRepository($this->transactionRepository);
        $this->profileRepository = new ProfileRepository;
        $this->stockRepository = new StockRepository;
        $this->portfolioRepository = new PortfolioRepository;
    }

    public function buildDashboard(): Dashboard
    {
        // build tickers and fetch remote stock data
        $symbols = $this->symbolRepository->buildSymbols();
        $this->profileRepository->fetchProfiles($symbols);

        // retrieve all transactions
        $allTransactions = $this->transactionRepository->getTransactionBySymbol();

        // calculate all stock indicators
        $totalPortfolioValue = $this->calculateTotalPortfolioValue($allTransactions);
        $stocks = $this->stockRepository->buildStocks($allTransactions, $totalPortfolioValue);
        $portfolio = $this->portfolioRepository->buildPortfolio($stocks, $totalPortfolioValue);

        return new Dashboard(
            $portfolio,
            new Chart($stocks)
        );
    }

    private function calculateTotalPortfolioValue($allTransactions): float
    {
        $result = 0;
        foreach ($allTransactions as $transactionsForStock) {
            $volumeOfShares = $transactionsForStock->sum('quantity');
            if ($volumeOfShares === 0) continue; // skip fully sold stocks
            $price = $transactionsForStock[0]->firstProfile()->price;
            $result += $price * $volumeOfShares;
        }

        return round($result, 2);
    }
}
