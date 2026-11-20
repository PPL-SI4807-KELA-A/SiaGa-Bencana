<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_login_page_and_authenticated_user_is_redirected_away(): void
    {
        $this->get(route('login'))->assertOk();

        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(route('dasbor'));
    }

    public function test_valid_credentials_authenticate_user(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@siagabencana.test',
            'password' => 'kata-sandi-uji',
        ]);

        $this->post(route('login.proses'), [
            'email' => 'admin@siagabencana.test',
            'password' => 'kata-sandi-uji',
            'remember' => true,
        ])->assertRedirect(route('dasbor'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_show_indonesian_error_and_remain_guest(): void
    {
        User::factory()->create([
            'email' => 'admin@siagabencana.test',
            'password' => 'kata-sandi-uji',
        ]);

        $this->from(route('login'))
            ->post(route('login.proses'), [
                'email' => 'admin@siagabencana.test',
                'password' => 'salah',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'Email atau kata sandi salah.']);

        $this->assertGuest();
    }

    public function test_public_posko_page_shows_the_appropriate_access_button(): void
    {
        $this->get(route('posko.publik'))
            ->assertOk()
            ->assertSee('Masuk')
            ->assertSee('href="'.route('login').'"', false)
            ->assertDontSee('href="'.route('dasbor').'"', false);

        $this->actingAs(User::factory()->create())
            ->get(route('posko.publik'))
            ->assertOk()
            ->assertSee('Dasbor')
            ->assertSee('href="'.route('dasbor').'"', false)
            ->assertDontSee('href="'.route('login').'"', false);
    }

    public function test_logout_ends_session_and_redirects_to_public_posko(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('logout'))
            ->assertRedirect('/posko')
            ->assertSessionHas('success', 'Anda telah keluar.');

        $this->assertGuest();
    }
}
