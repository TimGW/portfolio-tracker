<?php

namespace App\Imports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;

class DeGiroDataImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            $service_fee = 0.0;
            if(($row[14]) != null) {
                $service_fee = str_replace(',', '.', $row[14]);
            }

            return new Transaction([
                'purchased_date' => $row[0],
                'purchased_time' => $row[1],
                'product' => $row[2],
                'isin' => $row[3],
                'exchange' => $row[4],
                'place_of_execution' => $row[5],
                'quantity' => $row[6],
                'closing_rate' => str_replace(',', '.', $row[7]),
                'local_value' => str_replace(',', '.', $row[9]),
                'value' => str_replace(',', '.', $row[11]),
                'service_fee' => $service_fee,
                'total' => str_replace(',', '.', $row[16]),
                'currency' => $row[8],
                'user_id' => Auth::id()
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
