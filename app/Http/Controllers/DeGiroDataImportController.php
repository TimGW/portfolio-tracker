<?php

namespace App\Http\Controllers;

use App\Imports\DeGiroDataImport;
use Maatwebsite\Excel\Facades\Excel;

class DeGiroDataImportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function import()
    {
        Excel::import(new DeGiroDataImport, request()->file('file'));

        return back();
    }
}
