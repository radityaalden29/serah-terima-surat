<?php

namespace App\Exports;

use App\Models\Surat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuratExport implements FromCollection, WithHeadings
{

    public function collection()
    {
        return Surat::select(
            'no_agenda',
            'jenis',
            'pengirim',
            'penerima',
            'perihal',
            'tanggal',
            'status'
        )->get();
    }
  

    public function headings(): array
    {
        return [
            'No Agenda',
            'Jenis Surat',
            'Pengirim',
            'Penerima',
            'Perihal',
            'Tanggal',
            'Status'
        ];
    }

}
