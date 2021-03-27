<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Portfolio extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'portfolio';

    protected $fillable = [
        'total_current_value',
        'total_growth',
        'total_invested',
        'total_profit',
        'user_id',
        'currency',
        'updated_at'
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
