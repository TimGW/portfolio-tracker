<?php

namespace App\Repositories;

use App\Models\Stock;

class StocksRepositoryImpl implements StocksRepository
{
    
    public function getForUser($userId) 
    {
        return Stock::where('user_id', $userId)->get();
    }
}
