<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutBersamaTest extends TestCase
{
    use RefreshDatabase;

    public function test_relawan_tidak_melihat_menu_admin(): void
    {
        $this->actingAs(User::factory()->relawanPosko(1)->create())
            ->get(route('dasbor'))
            ->assertOk()
            ->assertDontSee(route('barang.index'))
            ->assertDontSee(route('kategori-bencana.index'))
            ->assertSee(route('stok-posko.index'));
    }

    public function test_admin_melihat_menu_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dasbor'))
            ->assertOk()
            ->assertSee(route('barang.index'))
            ->assertSee(route('kategori-bencana.index'));
    }

    public function test_relawan_ke_halaman_admin_mendapat_403_dengan_tombol_kembali(): void
    {
        $this->actingAs(User::factory()->relawanPosko(1)->create())
            ->get(route('barang.index'))
            ->assertForbidden()
            ->assertSee('Kembali');
    }

    public function test_tamu_tidak_melihat_label_admin_bpbd(): void
    {
        $this->get(route('posko.publik'))
            ->assertOk()
            ->assertDontSee('Admin BPBD');
    }

    public function test_judul_tab_dasbor(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dasbor'))
            ->assertSee('<title>Dasbor - SiaGa Bencana</title>', false);
    }
}