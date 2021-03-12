<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public $stock_name; // eigen input
    public $stock_sector;
    public $volume_of_shares; // eigen input
    public $ps_avg_price_purchased; // som van aankoopprijzen / volume
    public $ps_current_value; // data financialmodelingprep
    public $ps_profit; // (koers - gak ) * volume
    public $ps_profit_percentage; // profit per share / (gak * volume)
    public $stock_current_value; // waarde per aandeel * volume
    public $stock_weight; // huidige waarde aandelen / totale waarde portfolio
    public $stock_invested; // volume * gak

    public function __construct($transactionsForShare, $profile)
    {
        $this->stock_name = $profile['companyName'];
        $this->stock_sector = $profile['sector'];

        $this->volume_of_shares = array_sum(array_column($transactionsForShare, 'quantity'));
        $this->ps_avg_price_purchased = round(array_sum(array_column($transactionsForShare, 'closing_rate')) / $this->volume_of_shares, 3);
        $this->ps_current_value = round($profile['price'], 3);
        $this->ps_profit = round(($profile['price'] - $this->ps_avg_price_purchased) * $this->volume_of_shares, 2);
        $this->ps_profit_percentage = round(($this->ps_profit / ($this->ps_avg_price_purchased * $this->volume_of_shares)) * 100,2);
        $this->stock_current_value = round($this->ps_current_value * $this->volume_of_shares, 2);
        $this->stock_invested = $this->volume_of_shares * $this->ps_avg_price_purchased;
    }

    public function setStockWeight($total_portfolio_value)
    {
        $this->stock_weight = round(($this->stock_current_value / $total_portfolio_value) * 100);
    }

}
