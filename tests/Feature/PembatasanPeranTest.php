<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembatasanPeranTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_routes(): void
    {
        $this->get(route('wilayah-terdampak.index'))->assertRedirect(route('login'));
        $this->get(route('posko.index'))->assertRedirect(route('login'));
    }

    public function test_relawan_cannot_open_admin_routes(): void
    {
        $this->actingAs(User::factory()->relawanPosko(1)->create());

        $this->get(route('wilayah-terdampak.index'))
            ->assertForbidden()
            ->assertSee('Anda tidak memiliki akses ke halaman ini.');
        $this->get(route('posko.index'))->assertForbidden();
    }

    public function test_admin_can_open_wilayah_and_posko_admin_pages(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('wilayah-terdampak.index'))->assertOk();
        $this->get(route('posko.index'))->assertOk();
    }

    public function test_public_posko_page_remains_accessible_without_login(): void
    {
        $this->get(route('posko.publik'))->assertOk();
        $this->assertGuest();
    }
}
