<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'isin',
        'exchange',
        'quantity',
        'closing_rate',
        'service_fee',
        'currency',
        'user_id'
    ];

    function user() {
        return $this->belongsTo(User::class);
    }
}
