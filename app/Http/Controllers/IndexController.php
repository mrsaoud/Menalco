<?php

namespace App\Http\Controllers;


use DataTables;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class IndexController extends Controller
{
    public function index()
    {
        //$files = Storage::disk('ftp')->allFiles('/');
        //dd($files);
        return view('welcome');
    }

    //envoyer le array à datatables (frontedn)
    public function getTable(Request $request)
    {
        //remote here
        $file = public_path('Inventaire/' . $request->codeBar);
        
        if(!file_exists($file)){
            $fileFTP = file_get_contents('http://rapidis.ma/testinventaire/'.$request->codeBar);
           
            $fh = fopen('Inventaire/' . $request->codeBar, 'w');
            fwrite($fh, $fileFTP);
            Session::put('this', $request->codeBar);
            $data = $this->importCsv($file); 
            Session::put($request->codeBar, $data);
            return  DataTables::of($data)
                    ->smart(true)
                    ->addIndexColumn()
                    ->setRowId('row-{{$data[19]}}')
                    ->toJson();

        }elseif (Session::get($request->codeBar)) {

                $data = Session::get($request->codeBar);
                return  DataTables::of($data)
                    ->smart(true)
                    ->addIndexColumn()
                    ->setRowId('row-{{$data[19]}}')
                    ->toJson();

        } elseif ($file) {

                //si la session ne contien pas l'information
                Session::put('this', $request->codeBar);
                $data = $this->importCsv($file);
                Session::put($request->codeBar, $data);
                return  DataTables::of($data)
                    ->smart(true)
                    ->addIndexColumn()
                    ->setRowId('row-{{$data[19]}}')
                    ->toJson();
                    
        } else {
                return back()->with('error', 'Session introvable');
        }
           
    }

    // modifier la quantité et null 
    public function updateTable(Request $request)
    {
        $array = Session::get($request->session, []);
        foreach ($array as &$data) {

            if ($data[19] == $request->codeBar) {
                $data[5] += $request->q;
                $data[7] = "N";
            } else if ($data[5] == 0) {
                $data[7] = "O";
            } else {
                $data[7] = "N";
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
    function arrayToCsv(Request $request)
    {

        $i = 0;
        //remote
        $handle = fopen('Inventaire/' . $request->session, "r");
        $head[] = fgetcsv($handle, 1000, ",");
        $head1[] = fgetcsv($handle, 1, ",");
        $data = Session::get($request->session);

        $finalExport = array_merge($head, $head1, $data);
        $text = "";
        $j = 0;
        foreach ($finalExport as $fields) {
            if ($j < 2) {
                $numItems = count($fields);
                $i = 0;
                foreach ($fields as $value) {
                    if (++$i === $numItems) {
                        $text .= '"' . $value . '"';
                    } else {
                        if ($value == "1") {
                            $text .= '"' . $value . '",';
                        } else if (is_numeric($value)) {
                            $text .= $value . ",";
                        } else {
                            $text .= '"' . $value . '",';
                        }
                    }
                }
                $text .= "\n";
            } else {
                $numItems = count($fields);
                $i = 0;
                foreach ($fields as $value) {
                    if (++$i === $numItems) {
                        $text .= '"' . $value . '"';
                    } else {
                        if (is_numeric($value)) {
                            $text .= $value . ",";
                        } else {
                            $text .= '"' . $value . '",';
                        }
                    }
                }
                $text .= "\n";
            }
            $j++;
        }

            $fh = fopen('Inventaire/' . $request->session, 'w');
            fwrite($fh, $text) or die("Could not write file!");
        
            $file_local = Storage::disk('public')->get('vin');
            
            Storage::disk('ftp')->put('validé/'.$request->session,$file_local);
            Http::attach('file',$file_local,$request->session)->post('http://rapidis.ma/menalco/upload.php');
            fclose($fh);
            session()->forget($request->session);
            session()->forget('this');
    }

    function clearSession(){
        session()->forget('this');
    }

}
