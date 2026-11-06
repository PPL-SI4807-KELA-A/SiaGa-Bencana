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
