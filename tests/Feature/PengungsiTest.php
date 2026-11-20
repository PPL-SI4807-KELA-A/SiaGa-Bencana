<?php

namespace Tests\Feature;

use App\Models\Pengungsi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengungsiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_menampilkan_daftar_dan_sisa_kapasitas(): void
    {
        Pengungsi::create($this->data(['nama' => 'Siti Aminah']));

        $this->get(route('pengungsi.index'))
            ->assertOk()
            ->assertSee('Siti Aminah')
            ->assertSee('Sisa kapasitas posko')
            ->assertSee('249');
    }

    public function test_pengungsi_valid_disimpan_dengan_status_aktif(): void
    {
        $this->post(route('pengungsi.store'), $this->data(['nama' => '  Dewi Lestari  ']))
            ->assertRedirect(route('pengungsi.index'))
            ->assertSessionHas('success', 'Data pengungsi berhasil ditambahkan.');

        $this->assertDatabaseHas('pengungsi', [
            'nama' => 'Dewi Lestari',
            'status' => 'Aktif',
            'posko_id' => Pengungsi::POSKO_DUMMY_ID,
        ]);
    }

    public function test_isian_wajib_dan_nilai_pilihan_divalidasi(): void
    {
        $this->from(route('pengungsi.index'))
            ->post(route('pengungsi.store'), [])
            ->assertRedirect(route('pengungsi.index'))
            ->assertSessionHasErrors(['nama', 'jenis_kelamin', 'kelompok_usia', 'alamat_asal', 'status']);

        $this->post(route('pengungsi.store'), $this->data(['kelompok_usia' => 'Remaja']))
            ->assertSessionHasErrors('kelompok_usia');
    }

    public function test_nik_opsional_tetapi_harus_16_digit(): void
    {
        $this->post(route('pengungsi.store'), $this->data(['nik' => '']))->assertSessionHasNoErrors();
        $this->post(route('pengungsi.store'), $this->data(['nik' => '12345']))->assertSessionHasErrors('nik');
        $this->post(route('pengungsi.store'), $this->data(['nik' => '12345678901234ab']))->assertSessionHasErrors('nik');
    }

    public function test_nik_aktif_tidak_boleh_ganda_tetapi_boleh_jika_data_lama_sudah_pulang(): void
    {
        Pengungsi::create($this->data(['nik' => '3204000000000001']));

        $this->post(route('pengungsi.store'), $this->data(['nik' => '3204000000000001']))
            ->assertSessionHasErrors('nik');

        Pengungsi::query()->update(['status' => 'Kembali ke Rumah']);

        $this->post(route('pengungsi.store'), $this->data(['nik' => '3204000000000001']))
            ->assertSessionHasNoErrors();
    }

    public function test_pendaftaran_ditolak_jika_posko_penuh(): void
    {
        $now = now();
        Pengungsi::insert(array_map(
            fn ($i) => $this->data(['nama' => "Warga $i", 'created_at' => $now, 'updated_at' => $now]),
            range(1, Pengungsi::POSKO_DUMMY['kapasitas']),
        ));

        $this->post(route('pengungsi.store'), $this->data(['nama' => 'Warga Baru']))
            ->assertSessionHasErrors('kapasitas');

        $this->assertDatabaseMissing('pengungsi', ['nama' => 'Warga Baru']);
    }

    public function test_ubah_data_dan_status_berhasil(): void
    {
        $p = Pengungsi::create($this->data(['nik' => '3204000000000001']));

        $this->put(route('pengungsi.update', $p), $this->data([
            'nama' => 'Nama Baru',
            'nik' => '3204000000000001',
            'status' => 'Dirujuk',
        ]))
            ->assertRedirect(route('pengungsi.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pengungsi', ['id' => $p->id, 'nama' => 'Nama Baru', 'status' => 'Dirujuk']);
    }

    public function test_ubah_data_aktif_tidak_dianggap_nik_ganda_dengan_dirinya_sendiri(): void
    {
        $p = Pengungsi::create($this->data(['nik' => '3204000000000001']));

        $this->put(route('pengungsi.update', $p), $this->data(['nik' => '3204000000000001', 'alamat_asal' => 'Kp. Baru']))
            ->assertSessionHasNoErrors();
    }

    public function test_pencarian_dan_filter_status(): void
    {
        Pengungsi::create($this->data(['nama' => 'Budi Santoso', 'status' => 'Aktif']));
        Pengungsi::create($this->data(['nama' => 'Cecep Hidayat', 'status' => 'Kembali ke Rumah']));

        $this->get(route('pengungsi.index', ['q' => 'Budi']))
            ->assertSee('Budi Santoso')->assertDontSee('Cecep Hidayat');

        $this->get(route('pengungsi.index', ['status' => 'Kembali ke Rumah']))
            ->assertSee('Cecep Hidayat')->assertDontSee('Budi Santoso');
    }

    public function test_hapus_data_berhasil(): void
    {
        $p = Pengungsi::create($this->data());

        $this->delete(route('pengungsi.destroy', $p))
            ->assertRedirect(route('pengungsi.index'))
            ->assertSessionHas('success', 'Data pengungsi berhasil dihapus.');

        $this->assertDatabaseMissing('pengungsi', ['id' => $p->id]);
    }

    private function data(array $override = []): array
    {
        return array_merge([
            'posko_id' => Pengungsi::POSKO_DUMMY_ID,
            'nama' => 'Warga Uji',
            'jenis_kelamin' => 'Perempuan',
            'kelompok_usia' => 'Dewasa',
            'alamat_asal' => 'Kp. Cijagra RT 03/RW 07, Citeureup',
            'status' => 'Aktif',
        ], $override);
    }
}
