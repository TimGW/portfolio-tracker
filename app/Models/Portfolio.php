<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    private $profiles;
    private $all_transactions;

    public $stock_list;

    public $total_current_value;
    public $total_growth;
    public $total_invested;
    public $total_profit;

    public function __construct($profiles, $all_transactions)
    {
        $this->profiles = $profiles;
        $this->all_transactions = $all_transactions;

        $this->init();
    }

    function init()
    {
        $stock_list = array();
        $total_current_value = 0;

        foreach ($this->all_transactions as $transactionsForStock) {
            foreach ($this->profiles as $profile) {
                if ($transactionsForStock[0]['isin'] === $profile['isin']) {
                    $stock = new Stock($transactionsForStock, $profile['price']);
                    $total_current_value += $stock->stock_current_value;
                    $stock_list[] = $stock;
                }
            }
        }

        foreach ($stock_list as $stock) {
            $stock->setStockWeight($total_current_value);
        }

        $this->stock_list = $stock_list;
        $this->total_invested = array_sum(array_column($this->stock_list, 'stock_invested'));
        $this->total_profit = array_sum(array_column($this->stock_list, 'ps_profit'));
        $this->total_growth = round(($this->total_profit / $this->total_invested) * 100, 2);
        $this->total_current_value = $total_current_value;
    }
}
