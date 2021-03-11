<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    private $transactions;

    public $labels;
    public $dataset;
    public $colours;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;

        $this->labels = $this->buildLabels();
        $this->dataset = $this->buildData();
        $this->colours = $this->buildColors();
    }

    public function buildLabels()
    {
        $labels = array_column($this->transactions, 'product');

        $result = array();
        foreach ($labels as $value) {
            $result[] = mb_strimwidth(strtolower($value), 0, 10, "...");
        }
        return $result;
    }

    public function buildData()
    {
        $quantity = array_column($this->transactions, 'quantity');
        $onePercent = array_sum($quantity) / 100;

        $result = array();
        foreach ($quantity as $value) {
            $result[] = round($value / $onePercent, 2);
        }

        return $result;
    }

    public function buildColors()
    {
        $result = array();
        for ($i = 0; $i < count($this->transactions); $i++) {
            $result[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }
        return $result;
    }
}
