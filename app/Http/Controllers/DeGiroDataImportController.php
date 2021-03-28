<?php

namespace App\Http\Controllers;

use App\Imports\DeGiroDataImport;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        // remove current entries
        Transaction::where('user_id', Auth::id())->delete();

        // import new entries
        Excel::import(new DeGiroDataImport, request()->file('file'));

        return back();
    }
}
