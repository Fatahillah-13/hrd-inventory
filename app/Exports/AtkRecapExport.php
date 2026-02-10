<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AtkRecapExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $rows) {}

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Nama Barang', 'Quantity', 'Divisi'];
    }

    public function map($row): array
    {
        return [
            $row->item_name,
            $row->total_quantity,
            $row->division_name,
        ];
    }
}
