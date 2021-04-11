<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'stocks';

    protected $fillable = [
        'symbol',
        'volume_of_shares',
        'ps_avg_price_purchased',
        'ps_profit',
        'ps_profit_percentage',
        'stock_current_value',
        'stock_weight',
        'stock_invested',
        'service_fees',
        'portfolio_id'
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'symbol', 'symbol');
    }

    public function firstProfile()
    {
        return $this->profile()->get()->first();
    }

    public function transactions()
    {
        return Transaction::where('symbol', $this->symbol)->get();
    }
}
