<?php

namespace Database\Seeders\layanan;

use App\Models\Cabang;
use App\Models\JenisLayanan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();

        //? Cabang 1
        JenisLayanan::create([
            'nama' => 'Cuci Setrika',
            'cabang_id' => $cabang->id,
        ]);

        JenisLayanan::create([
            'nama' => 'Setrika',
            'cabang_id' => $cabang->id,
        ]);

        JenisLayanan::create([
            'nama' => 'Cuci Lipat',
            'cabang_id' => $cabang->id,
        ]);

        JenisLayanan::create([
            'nama' => 'Cuci Sepatu',
            'cabang_id' => $cabang->id,
        ]);
    }
}
