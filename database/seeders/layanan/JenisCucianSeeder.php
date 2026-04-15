<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\JenisCucian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisCucianSeeder extends Seeder
{

    private function createSeeder($nama, $deskripsi, $cabang_id, $lokasi, $alamat): JenisCucian
    {
        return JenisCucian::create([
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'cabang_id' => $cabang_id,
            'lokasi' => $lokasi,
            'alamat' => $alamat
        ]);
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang_2 = Cabang::where('id', 2)->onlyTrashed()->first();

        $this->createSeeder(
            'Sepatu Hitam',
            'sepatu berwarna hitam',
            $cabang->id,
            $cabang->lokasi,
            $cabang->alamat
        );

        $this->createSeeder(
            'Sepatu Berwarna',
            'sepatu berwarna-warna',
            $cabang->id,
            $cabang->lokasi,
            $cabang->alamat
        );
    }
}
