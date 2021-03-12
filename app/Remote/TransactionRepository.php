<?php


namespace App\Remote;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{

    public function allTransactionsForCurrentUserGroupedByColumn($column): array
    {
        $transactions = DB::table('transactions')
            ->where('user_id', '=', Auth::id())
            ->get()
            ->toArray();

        $transactionsArray = array_map(function ($value) {
            return (array)$value;
        }, $transactions);

        return $this->array_group($transactionsArray, $column);
    }

    private function array_group(array $data, $by_column): array
    {
        $result = array();
        foreach ($data as $element) {
            $result[$element[$by_column]][] = $element;
        }
        return $result;
    }
}
