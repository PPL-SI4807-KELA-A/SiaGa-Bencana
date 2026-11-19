<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PetugasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PetugasSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_seeder_creates_expected_accounts_with_hashed_passwords_and_is_repeatable(): void
    {
        $seeder = new PetugasSeeder();
        $seeder->run();
        $seeder->run();

        $this->assertDatabaseCount('users', 3);

        $this->assertPetugas('admin@siagabencana.test', 'Admin BPBD', null);
        $this->assertPetugas('relawan.bojongsoang@siagabencana.test', 'Relawan Posko', 1);
        $this->assertPetugas('relawan.dayeuhkolot@siagabencana.test', 'Relawan Posko', 2);
    }

    private function assertPetugas(string $email, string $role, ?int $poskoId): void
    {
        $user = User::where('email', $email)->firstOrFail();

        $this->assertSame($role, $user->role);
        $this->assertSame($poskoId, $user->posko_id);
        $this->assertTrue(Hash::check('password', $user->password));
    }
}
