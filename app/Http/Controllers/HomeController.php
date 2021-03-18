<?php

namespace App\Http\Controllers;

use App\Builder\PortfolioBuilder;
use App\Models\Chart;
use App\Remote\StockRepository;
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
        $transaction_repository = new TransactionRepository();
        $transactions = $transaction_repository->allTransactionsForCurrentUserGroupedBy("isin");

        if (empty($transactions)) {
            return view('empty');
        }

        $stock_repository = new StockRepository($transactions);
        $portfolio = $stock_repository->buildPortfolio();

        $chart = new Chart($portfolio->getStockList());

        return view('home', compact('portfolio', 'chart'));
    }
}
