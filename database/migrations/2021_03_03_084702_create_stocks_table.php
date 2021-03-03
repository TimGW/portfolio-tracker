<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->text('product', 255)->nullable();
            $table->text('symbol_isin', 255)->nullable();
            $table->text('quantity', 255)->nullable();
            $table->text('closing_price', 255)->nullable();
            $table->text('local_value', 255)->nullable();
            $table->text('value_in_euros', 255)->nullable();
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
