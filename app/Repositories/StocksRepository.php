<?php

namespace App\Repositories;

interface StocksRepository
{
    public function getForUser($userId);
}