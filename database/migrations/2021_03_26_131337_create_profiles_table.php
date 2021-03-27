<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 255);
            $table->float('price')->nullable();
            $table->float('beta')->nullable();
            $table->bigInteger('volAvg')->nullable();
            $table->bigInteger('mktCap')->nullable();
            $table->float('lastDiv')->nullable();
            $table->string('range', 255)->nullable();
            $table->float('changes')->nullable();
            $table->string('companyName', 255)->nullable();
            $table->string('currency', 255)->nullable();
            $table->string('cik', 255)->nullable();
            $table->string('isin', 255)->nullable();
            $table->string('cusip', 255)->nullable();
            $table->string('exchange', 255)->nullable();
            $table->string('exchangeShortName', 255)->nullable();
            $table->string('industry', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('ceo', 255)->nullable();
            $table->string('sector', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('fullTimeEmployees', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('zip', 255)->nullable();
            $table->float('dcfDiff')->nullable();
            $table->float('dcf')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('ipoDate', 255)->nullable();
            $table->boolean('defaultImage')->nullable();
            $table->boolean('isEtf')->nullable();
            $table->boolean('isActivelyTrading')->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
