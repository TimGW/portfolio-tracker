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

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
