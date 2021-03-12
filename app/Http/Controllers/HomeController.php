<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Remote\StockRepository;
use App\Models\Chart;
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
        $allTransactions = $transactionRepository->fetchItemsForUserGroupedByColumn("isin");
        $stockRepository = new StockRepository($allTransactions);

        $stocks = array();
        $totalPortfolioValue = 0;

        $profiles = $stockRepository->getStockProfiles();

        foreach ($allTransactions as $transactionsForShare) {
            foreach ($profiles as $profile) {
                if ($transactionsForShare[0]['isin'] === $profile['isin']) {
                    $stock = new Stock($transactionsForShare, $profile['price']);
                    $totalPortfolioValue += $stock->current_stock_value;
                    $stocks[] = $stock;
                }
            }
        }

        foreach ($stocks as $stock) {
            $stock->setWeight($totalPortfolioValue);
        }

        $chart = new Chart($stocks);

        return view('home', compact('stocks', 'chart'));
    }
}
