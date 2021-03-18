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
            $table->string('exchange', 255)->nullable();
            $table->string('stock_name', 255)->nullable();
            $table->string('stock_sector', 255)->nullable();
            $table->integer('volume_of_shares')->nullable();
            $table->float('ps_avg_price_purchased')->nullable();
            $table->float('ps_current_value')->nullable();
            $table->float('ps_profit')->nullable();
            $table->float('ps_profit_percentage')->nullable();
            $table->float('stock_current_value')->nullable();
            $table->float('stock_weight')->nullable();
            $table->float('stock_invested')->nullable();
            $table->float('service_fees')->nullable();
            $table->string('currency', 5)->nullable();
            $table->string('image', 255)->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users');
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
