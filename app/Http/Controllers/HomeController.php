<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Chart;
use Illuminate\Support\Facades\DB;

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
        $stocks = DB::table('stocks')
            ->where('user_id', '=', Auth::id())
            ->get()
            ->toArray();

        $quantity = array_column($stocks, 'quantity');  
        $onePercent = array_sum($quantity) / 100;
        foreach ($quantity as $value) {
            $result[] = ceil($value / $onePercent);
        };

        for ($i = 0; $i <= count($stocks); $i++) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }

        $chart = new Chart;
        $chart->labels = $this->mapLabels($stocks);
        $chart->dataset = $this->mapData($stocks);
        $chart->colours = $colours;
        return view('home', compact('stocks', 'chart'));
    }

    private function mapData($stocks) 
    {
        $quantity = array_column($stocks, 'quantity');  
        $onePercent = array_sum($quantity) / 100;

        foreach ($quantity as $value) {
            $result[] = round($value / $onePercent, 2);
        };
        return $result;
    }

    private function mapLabels($stocks) 
    {
        $labels = array_column($stocks, 'product');  

        foreach ($labels as $value) {
            $result[] = mb_strimwidth($value, 0, 10, "...");
        };        
        return $result;
    }
}
