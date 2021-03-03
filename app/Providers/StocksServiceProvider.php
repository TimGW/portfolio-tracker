<?php

namespace App\Providers;

use App\Repositories\StocksRepository;
use App\Repositories\StocksRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class StocksServiceProvider extends ServiceProvider 
{
    public function register()
    {
        $this->app->bind(StocksRepository::class, StocksRepositoryImpl::class);
    }
} 