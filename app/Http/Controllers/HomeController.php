<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
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
        $transaction_repository = new TransactionRepository();
        $all_transactions = $transaction_repository->allTransactionsForCurrentUserGroupedByColumn("isin");

        if (empty($all_transactions)) {
            return view('empty');
        }

        $stock_repository = new StockRepository($all_transactions);
        $profiles = $stock_repository->getStockProfiles();

        $portfolio = new Portfolio($profiles, $all_transactions);
        $chart = new Chart($portfolio->stock_list);

        return view('home', compact('portfolio', 'chart'));
    }
}
