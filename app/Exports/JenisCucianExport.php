<?php

namespace App\Exports;

use App\Models\JenisCucian;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JenisCucianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $cabang;

    public function __construct($cabang)
    {
        $this->cabang = $cabang;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return JenisCucian::query()
            ->join('cabang as c', 'c.id', '=', 'jenis_cucian.cabang_id')
            ->where('c.slug', $this->cabang)
            ->orderBy('jenis_cucian.id', 'asc')->get(['jenis_cucian.*', 'c.slug']);
    }

    public function map($data): array
    {
        return [
            $data->nama,
            $data->deskripsi,
            $data->slug,
        ];
    }

    public function headings(): array
    {
        return [
            'nama_cucian',
            'deskripsi',
            'cabang',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
    }
}
