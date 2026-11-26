<?php

namespace Tests\Feature;

use App\Models\FasilitasPosko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FasilitasPoskoTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('fasilitas-posko.index'))->assertRedirect(route('login'));
        $this->get(route('fasilitas-posko.create'))->assertRedirect(route('login'));
    }

    public function test_relawan_sees_only_facilities_from_own_posko(): void
    {
        $relawan = User::factory()->relawanPosko(11)->create();
        FasilitasPosko::create($this->data(11, 'Tenda'));
        FasilitasPosko::create($this->data(22, 'Genset'));

        $this->actingAs($relawan)->get(route('fasilitas-posko.index'))
            ->assertOk()->assertSee('Tenda')->assertDontSee('Genset');
    }

    public function test_valid_facility_is_saved_for_relawan_posko_and_user(): void
    {
        $relawan = User::factory()->relawanPosko(11)->create();

        $this->actingAs($relawan)->post(route('fasilitas-posko.store'), [
            'jenis_fasilitas' => 'Tenda', 'jumlah' => 5, 'kondisi' => 'Baik', 'keterangan' => 'Siap pakai',
            'posko_id' => 99, 'dicatat_oleh' => 999,
        ])->assertRedirect(route('fasilitas-posko.index'))
            ->assertSessionHas('success', 'Fasilitas posko berhasil ditambahkan.');

        $this->assertDatabaseHas('fasilitas_posko', [
            'posko_id' => 11, 'dicatat_oleh' => $relawan->id, 'jenis_fasilitas' => 'Tenda', 'jumlah' => 5,
        ]);
    }

    public function test_required_and_invalid_values_are_rejected(): void
    {
        $relawan = User::factory()->relawanPosko(11)->create();
        $this->actingAs($relawan)->post(route('fasilitas-posko.store'), [])->assertSessionHasErrors(['jenis_fasilitas', 'jumlah', 'kondisi']);

        $data = ['jenis_fasilitas' => 'Tenda', 'jumlah' => 0, 'kondisi' => 'Tidak diketahui'];
        $this->post(route('fasilitas-posko.store'), $data)->assertSessionHasErrors(['jumlah', 'kondisi']);
    }

    public function test_facility_name_must_be_unique_inside_same_posko_only(): void
    {
        $relawanA = User::factory()->relawanPosko(11)->create();
        $relawanB = User::factory()->relawanPosko(22)->create();
        FasilitasPosko::create($this->data(11, 'Tenda'));

        $this->actingAs($relawanA)->post(route('fasilitas-posko.store'), [
            'jenis_fasilitas' => 'Tenda', 'jumlah' => 2, 'kondisi' => 'Baik',
        ])->assertSessionHasErrors('jenis_fasilitas');

        $this->actingAs($relawanB)->post(route('fasilitas-posko.store'), [
            'jenis_fasilitas' => 'Tenda', 'jumlah' => 2, 'kondisi' => 'Baik',
        ])->assertRedirect(route('fasilitas-posko.index'));
    }

    public function test_relawan_cannot_edit_or_delete_another_poskos_facility(): void
    {
        $relawan = User::factory()->relawanPosko(11)->create();
        $fasilitas = FasilitasPosko::create($this->data(22, 'Tenda'));

        $this->actingAs($relawan)->get(route('fasilitas-posko.edit', $fasilitas))->assertNotFound();
        $this->put(route('fasilitas-posko.update', $fasilitas), [
            'jenis_fasilitas' => 'Tenda Baru', 'jumlah' => 3, 'kondisi' => 'Baik',
        ])->assertNotFound();
        $this->delete(route('fasilitas-posko.destroy', $fasilitas))->assertNotFound();

        $this->assertDatabaseHas('fasilitas_posko', ['id' => $fasilitas->id, 'posko_id' => 22, 'jenis_fasilitas' => 'Tenda']);
    }

    public function test_relawan_can_update_and_delete_own_facility(): void
    {
        $relawan = User::factory()->relawanPosko(11)->create();
        $fasilitas = FasilitasPosko::create($this->data(11, 'Tenda'));

        $this->actingAs($relawan)->put(route('fasilitas-posko.update', $fasilitas), [
            'jenis_fasilitas' => 'Tenda Utama', 'jumlah' => 4, 'kondisi' => 'Rusak Ringan',
        ])->assertRedirect(route('fasilitas-posko.index'));
        $this->assertDatabaseHas('fasilitas_posko', ['id' => $fasilitas->id, 'jenis_fasilitas' => 'Tenda Utama', 'posko_id' => 11]);

        $this->delete(route('fasilitas-posko.destroy', $fasilitas))->assertRedirect(route('fasilitas-posko.index'));
        $this->assertDatabaseMissing('fasilitas_posko', ['id' => $fasilitas->id]);
    }

    public function test_admin_sees_all_facilities_without_action_controls(): void
    {
        FasilitasPosko::create($this->data(11, 'Tenda'));
        FasilitasPosko::create($this->data(22, 'Genset'));

        $this->actingAs(User::factory()->create())->get(route('fasilitas-posko.index'))
            ->assertOk()->assertSee('Tenda')->assertSee('Genset')->assertDontSee('Tambah Fasilitas')->assertDontSee('modalHapus');
    }

    public function test_admin_cannot_open_or_submit_relawan_forms(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin)->get(route('fasilitas-posko.create'))->assertForbidden();
        $this->post(route('fasilitas-posko.store'), ['jenis_fasilitas' => 'Tenda', 'jumlah' => 1, 'kondisi' => 'Baik'])->assertForbidden();
    }

    public function test_relawan_without_posko_receives_forbidden_response(): void
    {
        $relawan = User::factory()->relawanPosko(null)->create();
        $this->actingAs($relawan)->get(route('fasilitas-posko.index'))
            ->assertForbidden()->assertSeeText('Akun Anda belum terhubung ke posko.');
        $this->get(route('fasilitas-posko.create'))->assertForbidden();
    }

    public function test_rusak_berat_facility_is_marked_in_the_table(): void
    {
        $relawan = User::factory()->relawanPosko(11)->create();
        FasilitasPosko::create($this->data(11, 'Tenda Rusak', 'Rusak Berat'));

        $this->actingAs($relawan)->get(route('fasilitas-posko.index'))
            ->assertOk()->assertSee('table-danger', false)->assertSee('Rusak Berat');
    }

    private function data(int $poskoId, string $jenis, string $kondisi = 'Baik'): array
    {
        return [
            'posko_id' => $poskoId,
            'jenis_fasilitas' => $jenis,
            'jumlah' => 3,
            'kondisi' => $kondisi,
            'keterangan' => null,
            'dicatat_oleh' => null,
        ];
    }
}
