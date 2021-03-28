<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 10)->nullable();
            $table->integer('volume_of_shares')->nullable()->default(0);
            $table->float('ps_avg_price_purchased')->nullable()->default(0);
            $table->float('ps_profit')->nullable()->default(0);
            $table->float('ps_profit_percentage')->nullable()->default(0);
            $table->float('stock_current_value')->nullable()->default(0);
            $table->float('stock_weight')->nullable()->default(0);
            $table->float('stock_invested')->nullable()->default(0);
            $table->float('service_fees')->nullable()->default(0);
            $table->foreignId('portfolio_id')->constrained('portfolio');
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
        Schema::dropIfExists('stocks');
    }
}
