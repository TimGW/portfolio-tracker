<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('purchased_date', 255)->nullable();
            $table->string('purchased_time', 255)->nullable();
            $table->string('product', 255)->nullable();
            $table->string('isin', 255);
            $table->string('exchange', 255)->nullable();
            $table->string('place_of_execution', 255)->nullable();
            $table->integer('quantity');
            $table->float('closing_rate')->nullable();
            $table->float('local_value')->nullable();
            $table->float('value')->nullable();
            $table->float('service_fee')->nullable();
            $table->float('total')->nullable();
            $table->string('currency', 255);
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
        Schema::dropIfExists('transactions');
    }
}
