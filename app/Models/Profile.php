<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Profile extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'profiles';

    protected $fillable = [
        'symbol',
        'price',
        'beta',
        'volAvg',
        'mktCap',
        'lastDiv',
        'range',
        'changes',
        'companyName',
        'currency',
        'cik',
        'isin',
        'cusip',
        'exchange',
        'exchangeShortName',
        'industry',
        'website',
        'description',
        'ceo',
        'sector',
        'country',
        'fullTimeEmployees',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'dcfDiff',
        'dcf',
        'image',
        'ipoDate',
        'defaultImage',
        'isEtf',
        'isActivelyTrading'
    ];
}
