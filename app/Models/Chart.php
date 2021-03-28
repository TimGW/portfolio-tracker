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

    public function __construct($stocks)
    {
        $this->stocks = $stocks;
        $this->labels = $this->buildLabels();
        $this->dataset = $this->buildData();
    }

    private function buildLabels(): array
    {
        $stocks = $this->stocks->unique('profile.sector');

        $result = array();
        foreach ($stocks as $stock) {
            $profile = $stock->firstProfile();
            $result[] = mb_strimwidth(strtolower($profile->sector), 0, 30, "...");
        }
        return $result;
    }

    private function buildData(): array
    {
        $result = array();
        foreach ($this->stocks->groupBy('profile.sector') as $sector) {
            $result[] = $sector->sum('stock_weight');
        }
        return $result;
    }
}
