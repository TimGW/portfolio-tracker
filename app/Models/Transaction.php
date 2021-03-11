<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'purchased_date',
        'purchased_time',
        'product',
        'isin',
        'exchange',
        'place_of_execution',
        'quantity',
        'closing_rate',
        'local_value',
        'value',
        'service_fee',
        'total',
        'currency',
        'user_id'
    ];

    function user() {
        return $this->belongsTo(User::class);
    }
}
