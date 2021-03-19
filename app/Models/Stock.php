<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $fillable = [
        'stock_ticker',
        'isin',
        'exchange',
        'stock_name',
        'stock_sector',
        'volume_of_shares',
        'ps_avg_price_purchased',
        'ps_current_value',
        'ps_profit',
        'ps_profit_percentage',
        'stock_current_value',
        'stock_weight',
        'stock_invested',
        'service_fees',
        'currency',
        'image',
        'user_id'
    ];

    private $isin;
    private $exchange;
    private $stock_name;
    private $stock_ticker;
    private $stock_sector;
    private $volume_of_shares;
    private $ps_avg_price_purchased;
    private $ps_current_value;
    private $ps_profit;
    private $ps_profit_percentage;
    private $stock_current_value;
    private $stock_weight;
    private $stock_invested;
    private $service_fees;
    private $currency;
    private $image;
    private $user_id;

    /**
     * @return mixed
     */
    public function getIsin()
    {
        return $this->isin;
    }

    /**
     * @param mixed $isin
     */
    public function setIsin($isin): void
    {
        $this->isin = $isin;
    }

    /**
     * @return mixed
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @param mixed $exchange
     */
    public function setExchange($exchange): void
    {
        $this->exchange = $exchange;
    }

    /**
     * @return mixed
     */
    public function getStockName()
    {
        return $this->stock_name;
    }

    /**
     * @param mixed $stock_name
     */
    public function setStockName($stock_name): void
    {
        $this->stock_name = $stock_name;
    }

    /**
     * @return mixed
     */
    public function getStockTicker()
    {
        return $this->stock_ticker;
    }

    /**
     * @param mixed $stock_ticker
     */
    public function setStockTicker($stock_ticker): void
    {
        $this->stock_ticker = $stock_ticker;
    }

    /**
     * @return mixed
     */
    public function getStockSector()
    {
        return $this->stock_sector;
    }

    /**
     * @param mixed $stock_sector
     */
    public function setStockSector($stock_sector): void
    {
        $this->stock_sector = $stock_sector;
    }

    /**
     * @return mixed
     */
    public function getVolumeOfShares()
    {
        return $this->volume_of_shares;
    }

    /**
     * @param mixed $volume_of_shares
     */
    public function setVolumeOfShares($volume_of_shares): void
    {
        $this->volume_of_shares = $volume_of_shares;
    }

    /**
     * @return mixed
     */
    public function getPsAvgPricePurchased()
    {
        return $this->ps_avg_price_purchased;
    }

    /**
     * @param mixed $ps_avg_price_purchased
     */
    public function setPsAvgPricePurchased($ps_avg_price_purchased): void
    {
        $this->ps_avg_price_purchased = $ps_avg_price_purchased;
    }

    /**
     * @return mixed
     */
    public function getPsCurrentValue()
    {
        return $this->ps_current_value;
    }

    /**
     * @param mixed $ps_current_value
     */
    public function setPsCurrentValue($ps_current_value): void
    {
        $this->ps_current_value = $ps_current_value;
    }

    /**
     * @return mixed
     */
    public function getPsProfit()
    {
        return $this->ps_profit;
    }

    /**
     * @param mixed $ps_profit
     */
    public function setPsProfit($ps_profit): void
    {
        $this->ps_profit = $ps_profit;
    }

    /**
     * @return mixed
     */
    public function getPsProfitPercentage()
    {
        return $this->ps_profit_percentage;
    }

    /**
     * @param mixed $ps_profit_percentage
     */
    public function setPsProfitPercentage($ps_profit_percentage): void
    {
        $this->ps_profit_percentage = $ps_profit_percentage;
    }

    /**
     * @return mixed
     */
    public function getStockCurrentValue()
    {
        return $this->stock_current_value;
    }

    /**
     * @param mixed $stock_current_value
     */
    public function setStockCurrentValue($stock_current_value): void
    {
        $this->stock_current_value = $stock_current_value;
    }

    /**
     * @return mixed
     */
    public function getStockWeight()
    {
        return $this->stock_weight;
    }

    /**
     * @param mixed $stock_weight
     */
    public function setStockWeight($stock_weight): void
    {
        $this->stock_weight = $stock_weight;
    }

    /**
     * @return mixed
     */
    public function getStockInvested()
    {
        return $this->stock_invested;
    }

    /**
     * @param mixed $stock_invested
     */
    public function setStockInvested($stock_invested): void
    {
        $this->stock_invested = $stock_invested;
    }

    /**
     * @return mixed
     */
    public function getServiceFees()
    {
        return $this->service_fees;
    }

    /**
     * @param mixed $service_fees
     */
    public function setServiceFees($service_fees): void
    {
        $this->service_fees = $service_fees;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
