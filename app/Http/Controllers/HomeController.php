<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use App\Remote\PortfolioRepository;
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

        $portfolioRepository = new PortfolioRepository($transactions);
        $portfolio = $portfolioRepository->getPortfolio();

        $chart = new Chart($portfolio->stocks);

        return view('home', compact('portfolio', 'chart'));
    }
}
