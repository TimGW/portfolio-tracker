<?php

namespace App\Remote;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{

    public function getGroupedTransactionsForUser(): Collection
    {
        return DB::table('transactions')
            ->where('user_id', '=', Auth::id())
            ->get()
            ->groupBy(['isin', 'exchange']);
    }
}
