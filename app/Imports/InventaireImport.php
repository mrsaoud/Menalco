<?php

namespace App\Imports;

use App\Models\Inventaire;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventaireImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            Inventaire::create([
            ]);
        }
        return $rows;
    }
}
