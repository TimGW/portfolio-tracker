<?php

namespace App\Http\Controllers;

use App\Dashboard\DashboardBuilder;
use App\Remote\TransactionRepository;

class HomeController extends Controller
{
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->transactionRepository = new TransactionRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (empty($this->transactionRepository->getTransactionByIsinAndExch()->all())) return view('empty');

        $dashboardBuilder = new DashboardBuilder($this->transactionRepository);

        $dashboard = $dashboardBuilder->buildDashboard();
        $portfolio = $dashboard->portfolio;
        $chart = $dashboard->chart;

        return view('home', compact('portfolio', 'chart'));
    }
}
