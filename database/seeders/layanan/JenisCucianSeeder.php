<?php

namespace Database\Seeders\layanan;

use App\Models\Cabang;
use App\Models\JenisCucian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisCucianSeeder extends Seeder
{

    private function createSeeder($nama, $deskripsi, $cabang_id): JenisCucian
    {
        return JenisCucian::create([
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'cabang_id' => $cabang_id,
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
        );

        $this->createSeeder(
            'Sepatu Berwarna',
            'sepatu berwarna-warna',
            $cabang->id,
        );

        $this->createSeeder(
            'Cucian Sehari-hari',
            'ya itu',
            $cabang->id,
        );
    }
}
