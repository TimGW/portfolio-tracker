<?php

namespace App\Remote;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{

    public function allTransactionsForCurrentUserGroupedBy(): array
    {
        $transactions = DB::table('transactions')
            ->where('user_id', '=', Auth::id())
            ->get()
            ->toArray();

        $transactionsArray = array_map(function ($value) {
            return (array)$value;
        }, $transactions);

        return $this->array_group($transactionsArray);
    }

    private function array_group(array $data): array
    {
        $result = array();
        foreach ($data as $element) {
            $result[$element['isin'].$element['exchange']][] = $element;
        }
        return $result;
    }
}
