<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    private $stocks;

    public $labels;
    public $dataset;
    public $colours;

    public function __construct($stocks)
    {
        $this->stocks = $stocks;

        $this->labels = $this->buildLabels();
        $this->dataset = $this->buildData();
        $this->colours = $this->buildColors();
    }

    private function buildLabels(): array
    {
        $labels = array_column($this->stocks, 'stock_name');
        $result = array();
        foreach ($labels as $value) {
            $result[] = mb_strimwidth(strtolower($value), 0, 10, "...");
        }
        return $result;
    }

    private function buildData(): array
    {
        $quantity = array_column($this->stocks, 'stock_weight');
        $onePercent = array_sum($quantity) / 100;

        $result = array();
        foreach ($quantity as $value) {
            $result[] = round($value / $onePercent, 2);
        }

        return $result;
    }

    private function buildColors(): array
    {
        $result = array();
        for ($i = 0; $i < count($this->stocks); $i++) {
            $result[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }
        return $result;
    }
}
