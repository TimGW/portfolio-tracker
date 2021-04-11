<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Metrics extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'metrics';

    protected $fillable = [
        'symbol',
        "date",
        "period",
        "revenuePerShare",
        "netIncomePerShare",
        "operatingCashFlowPerShare",
        "freeCashFlowPerShare",
        "cashPerShare",
        "bookValuePerShare",
        "tangibleBookValuePerShare",
        "shareholdersEquityPerShare",
        "interestDebtPerShare",
        "marketCap",
        "enterpriseValue",
        "peRatio",
        "priceToSalesRatio",
        "pocfratio",
        "pfcfRatio",
        "pbRatio",
        "ptbRatio",
        "evToSales",
        "enterpriseValueOverEBITDA",
        "evToOperatingCashFlow",
        "evToFreeCashFlow",
        "earningsYield",
        "freeCashFlowYield",
        "debtToEquity",
        "debtToAssets",
        "netDebtToEBITDA",
        "currentRatio",
        "interestCoverage",
        "incomeQuality",
        "dividendYield",
        "payoutRatio",
        "salesGeneralAndAdministrativeToRevenue",
        "researchAndDdevelopementToRevenue",
        "intangiblesToTotalAssets",
        "capexToOperatingCashFlow",
        "capexToRevenue",
        "capexToDepreciation",
        "stockBasedCompensationToRevenue",
        "grahamNumber",
        "roic",
        "returnOnTangibleAssets",
        "grahamNetNet",
        "workingCapital",
        "tangibleAssetValue",
        "netCurrentAssetValue",
        "investedCapital",
        "averageReceivables",
        "averagePayables",
        "averageInventory",
        "daysSalesOutstanding",
        "daysPayablesOutstanding",
        "daysOfInventoryOnHand",
        "receivablesTurnover",
        "payablesTurnover",
        "inventoryTurnover",
        "roe",
        "capexPerShare"
    ];
}
