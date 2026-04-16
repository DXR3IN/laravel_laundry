<?php

namespace Database\Seeders\akun;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cabang;
use App\Models\PegawaiLaundry;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabang = Cabang::where('id', 1)->first();
        $cabang2 = Cabang::where('id', 2)->onlyTrashed()->first();
        $rolePegawai = 'pegawai_laundry';

        //? Cabang 1
        $pegawai_laundry = User::factory()->create([
            'username' => 'Pegawai Laundry 1',
            'email' => 'pegawai@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $pegawai_laundry->assignRole($rolePegawai);
        PegawaiLaundry::create([
            'nama' => 'Pegawai Laundry 1',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1998-01-01',
            'telepon' => '082234567891',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pegawai_laundry->id,
        ]);

        $pegawai_laundry4 = User::factory()->create([
            'username' => 'Pegawai Laundry 4',
            'email' => 'pegawai4@gmail.com',
            'cabang_id' => $cabang->id,
        ]);
        $pegawai_laundry4->assignRole($rolePegawai);
        PegawaiLaundry::create([
            'nama' => 'Pegawai Laundry 4',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1997-03-03',
            'telepon' => '082234567893',
            'alamat' => 'Kelurahan Simokerto, Surabaya',
            'user_id' => $pegawai_laundry4->id,
        ]);
    }
}
