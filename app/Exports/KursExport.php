<?php

namespace App\Exports;

use App\Models\WebKurs;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KursExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return WebKurs::select('country', 'currency', 'bn_buy', 'bn_sell')->get();
    }

    public function headings(): array
    {
        return [
            'Country',
            'Currency',
            'BN Buy',
            'BN Sell',
        ];
    }
}
