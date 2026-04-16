<?php

namespace Database\Seeders\layanan;

use Carbon\Carbon;
use App\Models\Cabang;
use Illuminate\Database\Seeder;
use App\Models\LayananPrioritas;

class LayananPrioritasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();

        //? Cabang 1
        LayananPrioritas::create([
            'nama' => 'Reguler',
            'prioritas' => 1,
            'cabang_id' => $cabang->id,
        ]);

        LayananPrioritas::create([
            'nama' => 'Express',
            'prioritas' => 2,
            'cabang_id' => $cabang->id,
        ]);

        LayananPrioritas::create([
            'nama' => 'Kilat',
            'prioritas' => 3,
            'cabang_id' => $cabang->id,
        ]);
    }
}
