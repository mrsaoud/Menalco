<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\InventaireExport;
use App\Imports\InventaireImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;

class InventaireController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function index()
    {
        $users = User::get();

        return view('users', compact('users'));
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new InventaireExport, 'users.xlsx');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import()
    {
        Excel::import(new InventaireImport,url('Inventaire/VI2202210SES00000007.csv'),null,\Maatwebsite\Excel\Excel::CSV);
        return back();
    }
}
