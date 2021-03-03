<?php

namespace App\Imports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeGiroDataImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Stock([
            'product'     => $row['product'],
            'symbol'    => $row['symbol'], 
            'isin'    => $row['isin'], 
            'quantity'    => $row['quantity'], 
            'closing_price'    => $row['closing_price'], 
            'local_value'    => $row['local_value'], 
            'value_in_euros'    => $row['value_in_euros'], 
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
