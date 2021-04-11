<?php

namespace App\Http\Controllers;

use App\Dashboard\DashboardBuilder;
use App\Models\Stock;
use App\Remote\MetricsRepository;
use App\Remote\TransactionRepository;

class WishlistController extends Controller
{
    /**
     * @var TransactionRepository
     */
    private $metricsRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->metricsRepository = new MetricsRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $this->metricsRepository->fetchMetrics(["AAPL"]);

        return view('wishlist');
    }
}
