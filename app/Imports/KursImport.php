<?php

namespace App\Imports;

use App\Models\WebKurs;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Validators\Failure;

class KursImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToCollection, WithCalculatedFormulas, WithStartRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (! isset($row[2]) || ! isset($row[6]) || ! isset($row[7])) {
                continue;
            }
            WebKurs::create([
                // comma 2
                'country' => $row[2],
                'currency' => $row[2],
                'bn_buy' => number_format((float) $row[6], 2, '.', ''),
                'bn_sell' => number_format((float) $row[7], 2, '.', ''),
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function onError(\Throwable $e)
    {
        // Handle the error how you'd like.
        return 'asd';
    }

    public function onFailure(Failure ...$failures)
    {
        return 'asd';
    }
}
