<?php

namespace App\Imports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DeGiroDataImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Stock([
            'product'     => $row[0],
            'symbol_isin'    => $row[1],
            'quantity'    => $row[2],
            'closing_price'    => $row[3],
            'local_value'    => $row[4],
            'value_in_euros'    => $row[5],
        ]);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
