<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Stock extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'stocks';

    protected $fillable = [
        'symbol',
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
        'portfolio_id'
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
