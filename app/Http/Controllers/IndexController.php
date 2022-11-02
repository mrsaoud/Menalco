<?php

namespace App\Http\Controllers;


use DataTables;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    //envoyer le array à datatables (frontedn)
    public function getTable(Request $request)
    {
        $file = public_path('Inventaire/' . $request->codeBar . '.csv');
       if(Session::get($request->codeBar)) {
            $data = Session::get($request->codeBar);
            return  DataTables::of($data)
            ->smart(true)
            ->addIndexColumn()
            ->setRowId('row-{{$data[19]}}')
            ->toJson();

        } elseif ($file) {
            //si la session ne contien pas l'information
            $data = $this->importCsv($file);
            Session::put($request->codeBar, $data);
            
        } else{
            return back()->with('error', 'Session introvable');
        }
    }

    // modifier la quantité et null 
    public function updateTable(Request $request)
    {
        $array = Session::get($request->session, []);
        foreach($array as &$data) {
        
            if ($data[19] == $request->codeBar) {
               $data[6] +=$request->q;
               $data[7] ="O";
            }
            
        }
        
        $request->session()->put($request->session, $array);
        
    }

    
    //ajouter les donneé de le fichier csv à l'array
    public function importCsv($file)
    {
        $inventaire = $this->csvToArray($file);
        
        $data = collect();
        for ($i = 2; $i < count($inventaire); $i++) {
            $data->add($inventaire[$i]);
        }
        
        return $data->all();
    }


    //lire le fichier csv ligne par ligne
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }

        return $data;
    }

    //crée un fichier csv 
    function arrayToCsv(Request $request){
        $i = 0;
        $handle = fopen('Inventaire/' . $request->session . '.csv', "r");
        $head[] = fgetcsv($handle, 1000, ",");
        $head1[] = fgetcsv($handle, 1, ",");
        $data = Session::get($request->session);
        $footer[] = [0 => ''];

        $finalExport = array_merge($head,$head1,$data,$footer);
        //dd($finalExport);

        // EXPORT CSV
        $fp = fopen('Inventaire/tamporary.csv', 'w');    
        foreach ($finalExport as $rows) {
            fputcsv($fp, $rows , ",",'"',true);
        }    
        fclose($fp);
        
    }
}
