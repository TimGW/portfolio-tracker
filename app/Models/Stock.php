<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $fillable = [
        'product', 'symbol', 'isin', 'quantity', 'closing_price', 'local_value', 'value_in_euros'
    ];
}
