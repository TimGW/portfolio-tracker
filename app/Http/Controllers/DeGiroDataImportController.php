<?php

namespace App\Http\Controllers;

use App\Imports\DeGiroDataImport;
use Maatwebsite\Excel\Facades\Excel;

class DeGiroDataImportController extends Controller
{

    public function import()
    {
        Excel::import(new DeGiroDataImport, request()->file('file'));

        return back();
    }
}
