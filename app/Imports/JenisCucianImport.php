<?php

namespace App\Imports;

use App\Models\Cabang;
use App\Models\JenisCucian;
use App\Models\JenisPakaian;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JenisCucianImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $cabang = Cabang::where('slug', $row['cabang'])->first();
        $nama = JenisCucian::withTrashed()->where('nama', $row['nama_pakaian'])->where('cabang_id', $cabang->id)->first();
        if (empty($nama)) {
            return new JenisCucian([
                'nama' => $row['nama_pakaian'],
                'deskripsi' => $row['deskripsi'],
                'cabang_id' => $cabang->id,
            ]);
        }
    }
}
