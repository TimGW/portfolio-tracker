<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public $name;
    public $volume;
    public $price_ps_avg;
    public $current_rate;
    public $profit_ps;
    public $profit_ps_percentage;
    public $current_stock_value;
    public $weight;
    public $total_invested;

    public function __construct($transactionsForShare, $current_rate)
    {
        $this->name = $transactionsForShare[0]['product'];
        $this->volume = array_sum(array_column($transactionsForShare, 'quantity'));
        $this->price_ps_avg = round(array_sum(array_column($transactionsForShare, 'closing_rate')) / $this->volume, 3);
        $this->current_rate = round($current_rate, 3);
        $this->profit_ps = round(($current_rate - $this->price_ps_avg) * $this->volume, 2);
        $this->profit_ps_percentage = round(($this->profit_ps / ($this->price_ps_avg * $this->volume)) * 100,2);
        $this->current_stock_value = round($this->current_rate * $this->volume, 2);
        $this->total_invested = $this->volume * $this->price_ps_avg;
    }

    public function setWeight($total_portfolio_value)
    {
        $this->weight = round(($this->current_stock_value / $total_portfolio_value) * 100);
    }

}
