<?php

namespace Database\Seeders\akun;

use App\Models\OwnerLaundry;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerLaundrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleManajer = 'owner';
        $roleEmail = 'owner@gmail.com';
        $roleName = 'owner';

        $owner = User::factory()->create([
            'email' => $roleEmail,
            'username' => $roleName,
        ]);

        $owner->assignRole($roleManajer);

        OwnerLaundry::create(
            [
                'nama' => $owner->username,
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1997-01-01',
                'telepon' => '081234567891',
                'alamat' => 'Kelurahan Simokerto, Surabaya',
                'user_id' => $owner->id
            ]
        );
    }
}
