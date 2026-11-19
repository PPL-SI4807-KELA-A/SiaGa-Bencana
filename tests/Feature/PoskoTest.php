<?php

namespace Tests\Feature;

use App\Models\Posko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PoskoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_index_and_create_pages_are_available(): void
    {
        $this->get(route('posko.index'))->assertOk();
        $this->get(route('posko.create'))->assertOk();
    }

    public function test_valid_posko_is_saved_as_active_and_operating(): void
    {
        $this->post(route('posko.store'), $this->validData())
            ->assertRedirect(route('posko.index'))
            ->assertSessionHas('success', 'Data posko berhasil ditambahkan.');

        $this->assertDatabaseHas('posko', [
            'nama_posko' => 'Posko Taman Air Bojongsoang',
            'is_aktif' => true,
            'status_operasional' => 'Beroperasi',
        ]);
    }

    public function test_required_fields_are_validated(): void
    {
        $this->from(route('posko.create'))
            ->post(route('posko.store'), [])
            ->assertRedirect(route('posko.create'))
            ->assertSessionHasErrors([
                'nama_posko', 'alamat', 'desa_kelurahan', 'kecamatan',
                'penanggung_jawab', 'kapasitas', 'jumlah_terisi',
            ]);
    }

    public function test_zero_or_non_integer_capacity_is_rejected(): void
    {
        $zeroCapacity = $this->validData();
        $zeroCapacity['kapasitas'] = 0;
        $this->post(route('posko.store'), $zeroCapacity)->assertSessionHasErrors('kapasitas');

        $nonIntegerCapacity = $this->validData();
        $nonIntegerCapacity['kapasitas'] = '1.5';
        $this->post(route('posko.store'), $nonIntegerCapacity)->assertSessionHasErrors('kapasitas');
    }

    public function test_occupied_count_cannot_exceed_capacity(): void
    {
        $data = $this->validData();
        $data['jumlah_terisi'] = 51;

        $this->post(route('posko.store'), $data)->assertSessionHasErrors('jumlah_terisi');
    }

    public function test_posko_name_must_be_unique_but_can_remain_unchanged_on_update(): void
    {
        $posko = Posko::create($this->validData());

        $this->post(route('posko.store'), $this->validData())
            ->assertSessionHasErrors('nama_posko');

        $this->put(route('posko.update', $posko), $this->validData())
            ->assertRedirect(route('posko.index'))
            ->assertSessionHasNoErrors();
    }

    public function test_village_must_belong_to_selected_district(): void
    {
        $data = $this->validData();
        $data['kecamatan'] = 'Dayeuhkolot';
        $data['desa_kelurahan'] = 'Bojongsoang';

        $this->post(route('posko.store'), $data)->assertSessionHasErrors('desa_kelurahan');
    }

    public function test_detail_page_shows_posko_and_remaining_capacity_and_unknown_id_is_404(): void
    {
        $posko = Posko::create($this->validData());

        $this->get(route('posko.show', $posko))
            ->assertOk()
            ->assertSee($posko->nama_posko)
            ->assertSee('10');

        $this->get(route('posko.show', 999999))->assertNotFound();
    }

    public function test_valid_update_saves_changes(): void
    {
        $posko = Posko::create($this->validData());
        $updated = $this->validData();
        $updated['jumlah_terisi'] = 45;
        $updated['kapasitas'] = 60;

        $this->put(route('posko.update', $posko), $updated)
            ->assertRedirect(route('posko.index'))
            ->assertSessionHas('success', 'Data posko berhasil diperbarui.');

        $this->assertDatabaseHas('posko', [
            'id' => $posko->id,
            'kapasitas' => 60,
            'jumlah_terisi' => 45,
        ]);
    }

    public function test_capacity_cannot_be_reduced_below_occupied_count(): void
    {
        $data = $this->validData();
        $data['jumlah_terisi'] = 40;
        $posko = Posko::create($data);
        $updated = $data;
        $updated['kapasitas'] = 39;

        $this->put(route('posko.update', $posko), $updated)
            ->assertSessionHasErrors('jumlah_terisi');
    }

    public function test_destroy_route_is_not_registered_and_record_remains(): void
    {
        $posko = Posko::create($this->validData());

        $this->delete(route('posko.index').'/'.$posko->id)->assertMethodNotAllowed();

        $this->assertDatabaseHas('posko', ['id' => $posko->id]);
    }

    public function test_index_and_detail_show_readiness_badge(): void
    {
        // 40 dari 50 terisi (80%) harus berstatus Hampir Penuh
        $posko = Posko::create(array_merge($this->validData(), [
            'nama_posko' => 'Posko Badge Uji',
            'kapasitas' => 50,
            'jumlah_terisi' => 40,
        ]));

        $this->get(route('posko.index'))
            ->assertOk()
            ->assertSee('Hampir Penuh');

        $this->get(route('posko.show', $posko))
            ->assertOk()
            ->assertSee('Hampir Penuh');
    }

    public function test_operational_status_can_be_toggled_in_both_directions(): void
    {
        $posko = Posko::create($this->validData());

        $this->patch(route('posko.status-operasional', $posko))
            ->assertRedirect(route('posko.show', $posko))
            ->assertSessionHas('success', 'Posko ditandai Tidak Beroperasi.');
        $this->assertDatabaseHas('posko', ['id' => $posko->id, 'status_operasional' => 'Tidak Beroperasi']);

        $this->patch(route('posko.status-operasional', $posko), [
            'status_operasional' => 'Nilai Bebas',
            'is_aktif' => false,
        ])->assertSessionHas('success', 'Posko ditandai Beroperasi kembali.');

        $this->assertDatabaseHas('posko', [
            'id' => $posko->id,
            'status_operasional' => 'Beroperasi',
            'is_aktif' => true,
        ]);
    }

    public function test_active_status_can_be_toggled_in_both_directions_without_free_input(): void
    {
        $posko = Posko::create($this->validData());

        $this->patch(route('posko.aktif', $posko), [
            'is_aktif' => true,
            'status_operasional' => 'Tidak Beroperasi',
        ])->assertRedirect(route('posko.show', $posko))
            ->assertSessionHas('success', 'Posko berhasil dinonaktifkan.');

        $this->assertDatabaseHas('posko', [
            'id' => $posko->id,
            'is_aktif' => false,
            'status_operasional' => 'Beroperasi',
        ]);

        $this->patch(route('posko.aktif', $posko))
            ->assertSessionHas('success', 'Posko berhasil diaktifkan kembali.');

        $this->assertDatabaseHas('posko', ['id' => $posko->id, 'is_aktif' => true]);
    }

    private function validData(): array
    {
        return [
            'nama_posko' => 'Posko Taman Air Bojongsoang',
            'alamat' => 'Jl. Raya Bojongsoang No. 10',
            'desa_kelurahan' => 'Bojongsoang',
            'kecamatan' => 'Bojongsoang',
            'penanggung_jawab' => 'Siti Rahmawati',
            'kontak_penanggung_jawab' => '081234567890',
            'kapasitas' => 50,
            'jumlah_terisi' => 40,
        ];
    }
}
