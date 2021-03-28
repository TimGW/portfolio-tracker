<?php

namespace App\Remote;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TransactionRepository
{

    public function getTransactionBySymbol()
    {
        return Transaction::where('user_id', Auth::id())
            ->whereNotNull('symbol')
            ->get()
            ->groupBy(['symbol']);
    }

    public function getTransactionByIsinAndExch(): Collection
    {
        return Transaction::where('user_id', Auth::id())
            ->whereNotNull('isin')
            ->get()
            ->groupBy(['isin', 'exchange']);
    }

    public function saveTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            Transaction::where('user_id', Auth::id())
                ->where('isin', $transaction['isin'])
                ->where('exchange', $transaction['exchange'])
                ->update(['symbol' => $transaction['symbol']]);
        }
    }
}
