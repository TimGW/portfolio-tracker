<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    private $stock_list;
    private $total_current_value;
    private $total_growth;
    private $total_invested;
    private $total_profit;

    public function __construct($stock_list, $total_invested, $total_profit, $total_growth, $total_current_value)
    {
        $this->stock_list = $stock_list;
        $this->total_current_value = $total_current_value;
        $this->total_growth = $total_growth;
        $this->total_invested = $total_invested;
        $this->total_profit = $total_profit;
    }

    public function getTotalCurrentValue()
    {
        return $this->total_current_value;
    }

    public function getTotalGrowth()
    {
        return $this->total_growth;
    }

    public function getTotalProfit()
    {
        return $this->total_profit;
    }

    public function getStockList()
    {
        return $this->stock_list;
    }

    public function getTotalInvested()
    {
        return $this->total_invested;
    }
}
