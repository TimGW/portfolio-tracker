<?php


namespace App\Remote;


use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{

    public function allTransactionsForCurrentUserGroupedBy($column): array
    {
        $transactions = DB::table('transactions')
            ->where('user_id', '=', Auth::id())
            ->get()
            ->toArray();

        $transactionsArray = array_map(function ($value) {
            return (array)$value;
        }, $transactions);

        $result = $this->array_group($transactionsArray, $column);

        foreach ($result as $key => $transactionsForShare) {

            $volume_of_shares = array_sum(array_column($transactionsForShare, 'quantity'));
            if ($volume_of_shares == 0) continue;

            $ps_avg_price_purchased = round(array_sum(array_column($transactionsForShare, 'closing_rate')) / count($transactionsForShare), 2);
            $total_service_fee = round(array_sum(array_column($transactionsForShare, 'service_fee')), 2);
            $currency = $transactionsForShare[0]['currency'];
            $exchange = $transactionsForShare[0]['exchange'];

            $this->save($key, $volume_of_shares, $ps_avg_price_purchased, $total_service_fee, $exchange, $currency);
        }

        return $result;
    }

    private function array_group(array $data, $by_column): array
    {
        $result = array();
        foreach ($data as $element) {
            $result[$element[$by_column]][] = $element;
        }
        return $result;
    }

    private function save($key, $volume_of_shares, $ps_avg_price_purchased, $total_service_fee, $exchange, $currency)
    {
       Stock::updateOrCreate(
            ['isin' => $key, 'user_id' => Auth::id()],
            [
                'volume_of_shares' => $volume_of_shares,
                'ps_avg_price_purchased' => $ps_avg_price_purchased,
                'service_fees' => $total_service_fee,
                'exchange' => $exchange,
                'currency' => $currency
            ]
        );
    }
}
