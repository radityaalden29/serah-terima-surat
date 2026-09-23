<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class ActivityLogExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected ?string $aksi;
    protected ?string $search;

    public function __construct(?string $aksi = null, ?string $search = null)
    {
        $this->aksi   = $aksi;
        $this->search = $search;
    }

    public function query()
    {
        return ActivityLog::query()
            ->when($this->aksi, fn ($q) => $q->where('aksi', $this->aksi))
            ->when($this->search, fn ($q) => $q->where('no_agenda', 'like', "%{$this->search}%"))
            ->latest();
    }

    public function headings(): array
    {
        return ['#', 'Aksi', 'No Agenda', 'Keterangan', 'Waktu'];
    }

    public function map($log): array
    {
        static $i = 0;
        $i++;

        return [
            $i,
            $log->aksi,
            $log->no_agenda ?? '-',
            $log->keterangan ?? '-',
            \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
