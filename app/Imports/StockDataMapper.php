<?php

namespace App\Imports;

class StockDataMapper {

  public function mapExchangeToExchCode($input) {
    switch (strtoupper($input)) {
        case "EAM":
            return "NA";
        case "NSY":
        case "NDQ":
            return "US";
        default: return "";
    }
}

public function mapExchangeTickers($input) {
    switch (strtoupper($input)) {
        case "EAM":
            return "AS";
        case "NDQ":
        case "NSY":
        default: return "";
    }
}

}
?>
