<?php

namespace App\Imports;

use App\Models\PoItem;
use App\Models\Sizerun;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PoItemImport implements ToArray, WithCalculatedFormulas
{
    use Importable;

    /**
     * @param Collection $collection
     */
    public function array(array $rows)
    {
        foreach ($rows as $rowKey => $row) {
            if ($rowKey > 2 && !empty($row[0])) {
                $poItem = PoItem::where('po_number', $row[0])->first();
                if ($poItem === null) {
                    $sizerun = Sizerun::create([
                        'size_3t' => $row[5],
                        'size_4' => $row[6],
                        'size_4t' => $row[7],
                        'size_5' => $row[8],
                        'size_5t' => $row[9],
                        'size_6' => $row[10],
                        'size_6t' => $row[11],
                        'size_7' => $row[12],
                        'size_7t' => $row[13],
                        'size_8' => $row[14],
                        'size_8t' => $row[15],
                        'size_9' => $row[16],
                        'size_9t' => $row[17],
                        'size_10' => $row[18],
                        'size_10t' => $row[19],
                        'size_11' => $row[20],
                        'size_11t' => $row[21],
                        'size_12' => $row[22],
                        'size_12t' => $row[23],
                        'size_13' => $row[24],
                        'size_13t' => $row[25],
                        'size_14' => $row[26],
                        'size_14t' => $row[27],
                        'size_15' => $row[28],
                        'qty_total' => $row[29],
                    ]);

                    PoItem::create([
                        'po_number' => trim($row[0]),
                        'model_name' => trim($row[1]),
                        'cgac' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
                        'destination' => trim($row[3]),
                        'gender' => trim($row[4]),
                        'sizerun_id' => $sizerun->id,
                    ]);
                } else {
                    $poItem->update([
                        'model_name' => trim($row[1]),
                        'cgac' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
                        'destination' => trim($row[3]),
                        'gender' => trim($row[4]),
                    ]);

                    $poItem->sizerun()->update([
                        'size_3t' => $row[5],
                        'size_4' => $row[6],
                        'size_4t' => $row[7],
                        'size_5' => $row[8],
                        'size_5t' => $row[9],
                        'size_6' => $row[10],
                        'size_6t' => $row[11],
                        'size_7' => $row[12],
                        'size_7t' => $row[13],
                        'size_8' => $row[14],
                        'size_8t' => $row[15],
                        'size_9' => $row[16],
                        'size_9t' => $row[17],
                        'size_10' => $row[18],
                        'size_10t' => $row[19],
                        'size_11' => $row[20],
                        'size_11t' => $row[21],
                        'size_12' => $row[22],
                        'size_12t' => $row[23],
                        'size_13' => $row[24],
                        'size_13t' => $row[25],
                        'size_14' => $row[26],
                        'size_14t' => $row[27],
                        'size_15' => $row[28],
                        'qty_total' => $row[29],
                    ]);
                }
            }
        }
    }
}