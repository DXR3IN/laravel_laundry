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
                'name' => $owner->username,
                'user_id' => $owner->id
            ]
        );
    }
}
