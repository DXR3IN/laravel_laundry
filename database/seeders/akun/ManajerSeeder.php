<?php

namespace Database\Seeders\akun;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cabang;
use App\Models\ManajerLaundry;
use Illuminate\Database\Seeder;

class ManajerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $roleManajer = 'manajer_laundry';

        //? Cabang 1
        $manajer_laundry = User::factory()->create([
            'username' => 'Manajer Laundry 1',
            'email' => 'manajer@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $manajer_laundry->assignRole($roleManajer);
        ManajerLaundry::create([
            'nama' => 'Manajer Laundry 1',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1997-01-01',
            'telepon' => '081234567891',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $manajer_laundry->id,
        ]);
    }
}
