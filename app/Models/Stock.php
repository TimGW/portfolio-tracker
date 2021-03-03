<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $fillable = [
        'product', 'symbol_isin', 'quantity', 'closing_price', 'local_value', 'value_in_euros', 'user_id'
    ];

    function user() {
        return $this->belongsTo(User::class);
    }
}
