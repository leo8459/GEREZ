<?php

namespace App\Imports;

use App\Models\Rezago;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RezagosImport implements ToCollection, WithHeadingRow
{
    public function headingRow(): int { return 1; }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $codigo = trim((string)($row['codigo'] ?? ''));
            if ($codigo === '') continue;

            Rezago::updateOrCreate(
                ['codigo' => $codigo],
                [
                    'destinatario' => trim((string)($row['destinatario'] ?? '')),
                    'telefono'     => trim((string)($row['telefono'] ?? '')),
                    'peso'         => $row['peso'] !== null ? (float)$row['peso'] : null,
                    'aduana'       => trim((string)($row['aduana'] ?? '')),
                    'zona'         => trim((string)($row['zona'] ?? '')),
                    'tipo'         => trim((string)($row['tipo'] ?? '')),
                    'estado'       => 'PRE REZAGO',
                    'ciudad'       => trim((string)($row['ciudad'] ?? '')),
                ]
            );
        }
    }
}
