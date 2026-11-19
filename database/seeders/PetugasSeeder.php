<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = [
            [
                'name' => 'Admin BPBD Kab. Bandung',
                'email' => 'admin@siagabencana.test',
                'role' => 'Admin BPBD',
                'posko_id' => null,
            ],
            [
                'name' => 'Relawan Posko Bojongsoang',
                'email' => 'relawan.bojongsoang@siagabencana.test',
                'role' => 'Relawan Posko',
                'posko_id' => 1,
            ],
            [
                'name' => 'Relawan Posko Dayeuhkolot',
                'email' => 'relawan.dayeuhkolot@siagabencana.test',
                'role' => 'Relawan Posko',
                'posko_id' => 2,
            ],
        ];

        foreach ($petugas as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [...$data, 'password' => Hash::make('password')],
            );
        }
    }
}
