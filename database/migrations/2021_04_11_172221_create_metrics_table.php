<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->string("symbol");
            $table->string("date")->nullable();
            $table->string("period")->nullable();
            $table->decimal("revenuePerShare", 30, 20)->nullable();
            $table->decimal("netIncomePerShare", 30, 20)->nullable();
            $table->decimal("operatingCashFlowPerShare", 30, 20)->nullable();
            $table->decimal("freeCashFlowPerShare", 30, 20)->nullable();
            $table->decimal("cashPerShare", 30, 20)->nullable();
            $table->decimal("bookValuePerShare", 30, 20)->nullable();
            $table->decimal("tangibleBookValuePerShare", 30, 20)->nullable();
            $table->decimal("shareholdersEquityPerShare", 30, 20)->nullable();
            $table->decimal("interestDebtPerShare", 30, 20)->nullable();
            $table->bigInteger("marketCap")->nullable();
            $table->decimal("enterpriseValue", 40, 20)->nullable();
            $table->decimal("peRatio", 30, 20)->nullable();
            $table->decimal("priceToSalesRatio", 30, 20)->nullable();
            $table->decimal("pocfratio", 30, 20)->nullable();
            $table->decimal("pfcfRatio", 30, 20)->nullable();
            $table->decimal("pbRatio", 30, 20)->nullable();
            $table->decimal("ptbRatio", 30, 20)->nullable();
            $table->decimal("evToSales", 30, 20)->nullable();
            $table->decimal("enterpriseValueOverEBITDA", 30, 20)->nullable();
            $table->decimal("evToOperatingCashFlow", 30, 20)->nullable();
            $table->decimal("evToFreeCashFlow", 30, 20)->nullable();
            $table->decimal("earningsYield", 30, 20)->nullable();
            $table->decimal("freeCashFlowYield", 30, 20)->nullable();
            $table->decimal("debtToEquity", 30, 20)->nullable();
            $table->decimal("debtToAssets", 30, 20)->nullable();
            $table->decimal("netDebtToEBITDA", 30, 20)->nullable();
            $table->decimal("currentRatio", 30, 20)->nullable();
            $table->decimal("interestCoverage", 30, 20)->nullable();
            $table->decimal("incomeQuality", 30, 20)->nullable();
            $table->decimal("dividendYield", 30, 20)->nullable();
            $table->decimal("payoutRatio", 30, 20)->nullable();
            $table->decimal("salesGeneralAndAdministrativeToRevenue", 30, 20)->nullable();
            $table->decimal("researchAndDdevelopementToRevenue", 30, 20)->nullable();
            $table->decimal("intangiblesToTotalAssets", 30, 20)->nullable();
            $table->decimal("capexToOperatingCashFlow", 30, 20)->nullable();
            $table->decimal("capexToRevenue", 30, 20)->nullable();
            $table->decimal("capexToDepreciation", 30, 20)->nullable();
            $table->decimal("stockBasedCompensationToRevenue", 30, 20)->nullable();
            $table->decimal("grahamNumber", 30, 20)->nullable();
            $table->decimal("roic", 30, 20)->nullable();
            $table->decimal("returnOnTangibleAssets", 30, 20)->nullable();
            $table->decimal("grahamNetNet", 30, 20)->nullable();
            $table->bigInteger("workingCapital")->nullable();
            $table->bigInteger("tangibleAssetValue")->nullable();
            $table->bigInteger("netCurrentAssetValue")->nullable();
            $table->decimal("investedCapital", 30, 20)->nullable();
            $table->bigInteger("averageReceivables")->nullable();
            $table->bigInteger("averagePayables")->nullable();
            $table->bigInteger("averageInventory")->nullable();
            $table->decimal("daysSalesOutstanding", 30, 20)->nullable();
            $table->decimal("daysPayablesOutstanding", 30, 20)->nullable();
            $table->decimal("daysOfInventoryOnHand", 30, 20)->nullable();
            $table->decimal("receivablesTurnover", 30, 20)->nullable();
            $table->decimal("payablesTurnover", 30, 20)->nullable();
            $table->decimal("inventoryTurnover", 30, 20)->nullable();
            $table->decimal("roe", 30, 20)->nullable();
            $table->decimal("capexPerShare", 30, 20)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metrics');
    }
}
