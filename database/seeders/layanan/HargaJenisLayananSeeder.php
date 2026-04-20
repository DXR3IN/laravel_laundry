<?php

namespace Database\Seeders\layanan;

use App\Enums\JenisSatuanLayanan;
use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\JenisLayanan;
use App\Models\JenisPakaian;
use Illuminate\Database\Seeder;
use App\Models\HargaJenisLayanan;
use App\Models\JenisCucian;
use App\Models\LayananPrioritas;

class HargaJenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang2 = Cabang::where('id', 2)->onlyTrashed()->first();

        //? Cabang 1
        $jenisLayananCuciLipat = JenisLayanan::where(['nama' => 'Cuci Lipat', 'cabang_id' => $cabang->id])->first();
        $jenisLayananCuciSetrika = JenisLayanan::where(['nama' => 'Cuci Setrika', 'cabang_id' => $cabang->id])->first();
        $jenisLayananCuciSepatu = JenisLayanan::where(['nama' => 'Cuci Sepatu', 'cabang_id' => $cabang->id])->first();

        // jenis cucian
        $jenisCucian_Pakaian = JenisCucian::where(['nama' => 'Pakaian Sehari-hari', 'cabang_id' => $cabang->id])->first();
        $jenisCucian_SepatuH = JenisCucian::where(['nama' => 'Sepatu Hitam', 'cabang_id' => $cabang->id])->first();

        // prioritas
        $reguler = LayananPrioritas::where(['nama' => 'reguler', 'cabang_id' => $cabang->id])->first();
        $express = LayananPrioritas::where(['nama' => 'express', 'cabang_id' => $cabang->id])->first();
        $kilat = LayananPrioritas::where(['nama' => 'kilat', 'cabang_id' => $cabang->id])->first();

        //? Seeder --> make Harga Jenis Layanan Kaos
        HargaJenisLayanan::create([
            'harga' => 5000,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'prioritas_id' => $reguler->id,
            'jenis_layanan_id' => $jenisLayananCuciLipat->id,
            'jenis_cucian_id' => $jenisCucian_Pakaian->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' => 10000,
            'jenis_satuan' => JenisSatuanLayanan::KG,
            'prioritas_id' => $kilat->id,
            'jenis_layanan_id' => $jenisLayananCuciSetrika->id,
            'jenis_cucian_id' => $jenisCucian_Pakaian->id,
            'cabang_id' => $cabang->id,
        ]);
        HargaJenisLayanan::create([
            'harga' =>  40000,
            'jenis_satuan' => JenisSatuanLayanan::UNIT,
            'prioritas_id' => $kilat->id,
            'jenis_layanan_id' => $jenisLayananCuciSepatu->id,
            'jenis_cucian_id' => $jenisCucian_SepatuH->id,
            'cabang_id' => $cabang->id,
        ]);
    }
}
