<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Repositories\StocksRepository;

class HomeController extends Controller
{
    protected $stocksRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stocks = $this->stocksRepository->getForUser(Auth::id());
        return view('home', compact('stocks'));
    }
}