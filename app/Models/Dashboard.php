<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Dashboard extends Model
{
    use HasFactory, Notifiable;

    public $portfolio;
    public $chart;

    public function __construct($portfolio, $chart)
    {
        $this->portfolio = $portfolio;
        $this->chart = $chart;
    }
}
