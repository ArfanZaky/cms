<?php

namespace App\Exports;

use App\Models\WebContacts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = WebContacts::query();
        if ($this->request->start_date && $this->request->end_date) {
            $data = $data->when($this->request->start_date, function ($query, $start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })->when($this->request->end_date, function ($query, $end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            });
        }

        $data = $data->orderBy('created_at', 'desc')
            ->get();

        $data = collect($data)->map(function ($item) {
            return [
                'id' => $item->id,
                'full_name' => $item->full_name,
                'email' => $item->email,
                'subject' => $item->subject,
                'description' => $item->description,
                'date' => date('d M Y H:i', strtotime($item->created_at)),
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'Email',
            'Subject',
            'Description',
            'Date',
        ];
    }
}
