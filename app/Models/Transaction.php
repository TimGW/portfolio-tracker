<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'transactions';

    protected $fillable = [
        'symbol',
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

    public function firstProfile()
    {
        return $this->hasOne(Profile::class, 'symbol', 'symbol')->get()->first();
    }
}
