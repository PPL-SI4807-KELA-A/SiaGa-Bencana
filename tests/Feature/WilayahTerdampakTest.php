<?php

namespace Tests\Feature;

use App\Models\WilayahTerdampak;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WilayahTerdampakTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_is_available(): void
    {
        $this->get(route('wilayah-terdampak.index'))->assertOk();
    }

    public function test_create_page_is_available(): void
    {
        $this->get(route('wilayah-terdampak.create'))->assertOk();
    }

    public function test_valid_data_is_saved_and_redirected_to_index(): void
    {
        $response = $this->post(route('wilayah-terdampak.store'), $this->validData());

        $response->assertRedirect(route('wilayah-terdampak.index'))
            ->assertSessionHas('success', 'Data wilayah terdampak berhasil ditambahkan.');
        $this->assertDatabaseHas('wilayah_terdampak', [
            'desa_kelurahan' => 'Bojongsoang',
            'tanggal_kejadian' => '2026-11-03',
        ]);
    }

    public function test_required_fields_are_validated(): void
    {
        $this->from(route('wilayah-terdampak.create'))
            ->post(route('wilayah-terdampak.store'), [])
            ->assertRedirect(route('wilayah-terdampak.create'))
            ->assertSessionHasErrors([
                'kecamatan', 'desa_kelurahan', 'tanggal_kejadian',
                'jumlah_kk_terdampak', 'status_penanganan',
            ]);
    }

    public function test_same_village_on_same_date_is_rejected_but_different_date_is_allowed(): void
    {
        WilayahTerdampak::create($this->validData());

        $this->from(route('wilayah-terdampak.create'))
            ->post(route('wilayah-terdampak.store'), $this->validData())
            ->assertSessionHasErrors('desa_kelurahan');

        $differentDate = $this->validData();
        $differentDate['tanggal_kejadian'] = '2026-11-02';

        $this->post(route('wilayah-terdampak.store'), $differentDate)
            ->assertRedirect(route('wilayah-terdampak.index'));
        $this->assertDatabaseCount('wilayah_terdampak', 2);
    }

    public function test_invalid_status_and_zero_households_are_rejected(): void
    {
        $invalidData = $this->validData();
        $invalidData['status_penanganan'] = 'Bahaya';
        $invalidData['jumlah_kk_terdampak'] = 0;

        $this->post(route('wilayah-terdampak.store'), $invalidData)
            ->assertSessionHasErrors(['status_penanganan', 'jumlah_kk_terdampak']);
    }

    public function test_detail_page_shows_existing_record_and_missing_record_returns_404(): void
    {
        $wilayah = WilayahTerdampak::create($this->validData());

        $this->get(route('wilayah-terdampak.show', $wilayah))
            ->assertOk()
            ->assertSee('Bojongsoang');

        $this->get(route('wilayah-terdampak.show', 999999))->assertNotFound();
    }

    public function test_edit_page_shows_existing_values(): void
    {
        $wilayah = WilayahTerdampak::create($this->validData());

        $this->get(route('wilayah-terdampak.edit', $wilayah))
            ->assertOk()
            ->assertSee('value="484"', false)
            ->assertSee('value="Tanggap Darurat"', false);
    }

    public function test_valid_update_saves_changes_and_redirects_to_index(): void
    {
        $wilayah = WilayahTerdampak::create($this->validData());
        $updated = $this->validData();
        $updated['jumlah_kk_terdampak'] = 600;

        $this->put(route('wilayah-terdampak.update', $wilayah), $updated)
            ->assertRedirect(route('wilayah-terdampak.index'))
            ->assertSessionHas('success', 'Data wilayah terdampak berhasil diperbarui.');

        $this->assertDatabaseHas('wilayah_terdampak', ['id' => $wilayah->id, 'jumlah_kk_terdampak' => 600]);
    }

    public function test_update_without_changing_village_and_date_is_allowed(): void
    {
        $wilayah = WilayahTerdampak::create($this->validData());
        $updated = $this->validData();
        $updated['keterangan'] = 'Keterangan diperbarui.';

        $this->put(route('wilayah-terdampak.update', $wilayah), $updated)
            ->assertRedirect(route('wilayah-terdampak.index'))
            ->assertSessionHasNoErrors();
    }

    public function test_update_rejects_village_and_date_combination_used_by_another_record(): void
    {
        $first = WilayahTerdampak::create($this->validData());
        $secondData = $this->validData();
        $secondData['desa_kelurahan'] = 'Tegalluar';
        $second = WilayahTerdampak::create($secondData);

        $this->from(route('wilayah-terdampak.edit', $second))
            ->put(route('wilayah-terdampak.update', $second), [
                ...$this->validData(),
                'keterangan' => 'Perubahan duplikat.',
            ])
            ->assertRedirect(route('wilayah-terdampak.edit', $second))
            ->assertSessionHasErrors('desa_kelurahan');

        $this->assertDatabaseHas('wilayah_terdampak', ['id' => $first->id, 'desa_kelurahan' => 'Bojongsoang']);
    }

    public function test_destroy_deletes_record_and_redirects_to_index(): void
    {
        $wilayah = WilayahTerdampak::create($this->validData());

        $this->delete(route('wilayah-terdampak.destroy', $wilayah))
            ->assertRedirect(route('wilayah-terdampak.index'))
            ->assertSessionHas('success', 'Data wilayah terdampak berhasil dihapus.');

        $this->assertDatabaseMissing('wilayah_terdampak', ['id' => $wilayah->id]);
    }

    public function test_index_filters_by_kecamatan_status_and_both_together(): void
    {
        WilayahTerdampak::create($this->validData());
        $dayeuhkolot = $this->validData();
        $dayeuhkolot['desa_kelurahan'] = 'Dayeuhkolot';
        $dayeuhkolot['kecamatan'] = 'Dayeuhkolot';
        $dayeuhkolot['status_penanganan'] = 'Siaga';
        WilayahTerdampak::create($dayeuhkolot);
        $dayeuhkolotTanggap = $dayeuhkolot;
        $dayeuhkolotTanggap['desa_kelurahan'] = 'Citeureup';
        $dayeuhkolotTanggap['status_penanganan'] = 'Tanggap Darurat';
        WilayahTerdampak::create($dayeuhkolotTanggap);

        $this->get(route('wilayah-terdampak.index', ['kecamatan' => 'Dayeuhkolot']))
            ->assertOk()
            ->assertViewHas('wilayahTerdampak', fn ($daftar) => $daftar->total() === 2 && $daftar->every(fn ($item) => $item->kecamatan === 'Dayeuhkolot'));

        $this->get(route('wilayah-terdampak.index', ['status_penanganan' => 'Siaga']))
            ->assertOk()
            ->assertViewHas('wilayahTerdampak', fn ($daftar) => $daftar->total() === 1 && $daftar->first()->status_penanganan === 'Siaga');

        $this->get(route('wilayah-terdampak.index', ['kecamatan' => 'Dayeuhkolot', 'status_penanganan' => 'Siaga']))
            ->assertOk()
            ->assertViewHas('wilayahTerdampak', fn ($daftar) => $daftar->total() === 1 && $daftar->first()->desa_kelurahan === 'Dayeuhkolot');
    }

    public function test_invalid_filter_values_are_ignored(): void
    {
        WilayahTerdampak::create($this->validData());
        $dayeuhkolot = $this->validData();
        $dayeuhkolot['desa_kelurahan'] = 'Dayeuhkolot';
        $dayeuhkolot['kecamatan'] = 'Dayeuhkolot';
        WilayahTerdampak::create($dayeuhkolot);

        $this->get(route('wilayah-terdampak.index', [
            'kecamatan' => 'Kecamatan Tidak Valid',
            'status_penanganan' => 'Status Tidak Valid',
        ]))
            ->assertOk()
            ->assertViewHas('wilayahTerdampak', fn ($daftar) => $daftar->total() === 2);
    }

    private function validData(): array
    {
        return [
            'kecamatan' => 'Bojongsoang',
            'desa_kelurahan' => 'Bojongsoang',
            'tanggal_kejadian' => '2026-11-03',
            'jumlah_kk_terdampak' => 484,
            'status_penanganan' => 'Tanggap Darurat',
            'keterangan' => 'Data simulasi.',
        ];
    }
}
