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
            $table->string('stock_ticker', 10)->nullable();
            $table->string('isin', 255)->nullable();
            $table->string('exchange', 255)->nullable()->default('');
            $table->string('stock_name', 255)->nullable()->default('');
            $table->string('stock_sector', 255)->nullable()->default('');
            $table->integer('volume_of_shares')->nullable()->default(0);
            $table->float('ps_avg_price_purchased')->nullable()->default(0);
            $table->float('ps_current_value')->nullable()->default(0);
            $table->float('ps_profit')->nullable()->default(0);
            $table->float('ps_profit_percentage')->nullable()->default(0);
            $table->float('stock_current_value')->nullable()->default(0);
            $table->float('stock_weight')->nullable()->default(0);
            $table->float('stock_invested')->nullable()->default(0);
            $table->float('service_fees')->nullable()->default(0);
            $table->string('currency', 5)->nullable()->default('');
            $table->string('image', 255)->nullable()->default('');
            $table->timestamps();
            $table->foreignId('portfolio_id')->constrained('portfolio');
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
