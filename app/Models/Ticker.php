<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    use HasFactory;

    public $isin;
    public $ticker;
    public $giro_exchange;
    public $bb_exchange;
    public $exch_appendix;

    public function __construct($isin, $giro_exchange)
    {
        $this->isin = $isin;
        $this->giro_exchange = $giro_exchange;
        $this->bb_exchange = $this->getBBExchangeCode();
        $this->exch_appendix = $this->getAppendix();
    }

    public function isCommonStock($value) {
        return strcasecmp($value, "Common Stock");
    }

    private function getBBExchangeCode() {
        switch (strtoupper($this->giro_exchange)) {
            case "EAM":
                return "NA";
            case "NSY":
            case "NDQ":
                return "US";
            default: return "";
        }
    }

    private function getAppendix() {
        switch (strtoupper($this->giro_exchange)) {
            case "EAM":
                return "AS";
            case "NDQ":
            case "NSY":
            default: return "";
        }
    }
}
