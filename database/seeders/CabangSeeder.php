<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cabang::create([
            'nama' => 'Top Laundry Utama',
            'lokasi' => 'Pekanbaru',
            'alamat' => 'Ya! Gitu',
        ]);
    }
}
