<?php

namespace Tests\Feature;

use App\Models\SumberBantuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SumberBantuanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function data(array $override = []): array
    {
        return array_merge([
            'nama' => 'PMI Kab. Bandung',
            'jenis' => 'komunitas',
            'kontak' => '022-5959012',
        ], $override);
    }

    private function buat(array $override = []): SumberBantuan
    {
        return SumberBantuan::create(array_merge($this->data(), ['aktif' => true], $override));
    }

    public function test_halaman_index_menampilkan_data(): void
    {
        $this->buat();

        $this->get(route('sumber-bantuan.index'))
            ->assertOk()
            ->assertSee('PMI Kab. Bandung');
    }

    public function test_tambah_sumber_bantuan_berhasil_dan_aktif(): void
    {
        $this->post(route('sumber-bantuan.store'), $this->data())
            ->assertRedirect(route('sumber-bantuan.index'));

        $this->assertDatabaseHas('sumber_bantuan', [
            'nama' => 'PMI Kab. Bandung',
            'jenis' => 'komunitas',
            'aktif' => true,
        ]);
    }

    public function test_nama_jenis_dan_kontak_wajib_diisi(): void
    {
        $this->post(route('sumber-bantuan.store'), [])
            ->assertSessionHasErrors(['nama', 'jenis', 'kontak']);

        $this->assertDatabaseCount('sumber_bantuan', 0);
    }

    public function test_jenis_harus_salah_satu_dari_empat_pilihan(): void
    {
        $this->post(route('sumber-bantuan.store'), $this->data(['jenis' => 'asing']))
            ->assertSessionHasErrors('jenis');
    }

    public function test_nama_ganda_pada_jenis_yang_sama_ditolak(): void
    {
        $this->buat();

        $this->post(route('sumber-bantuan.store'), $this->data(['nama' => 'pmi kab. bandung']))
            ->assertSessionHasErrors('nama');

        $this->assertDatabaseCount('sumber_bantuan', 1);
    }

    public function test_nama_sama_pada_jenis_berbeda_diperbolehkan(): void
    {
        $this->buat();

        $this->post(route('sumber-bantuan.store'), $this->data(['jenis' => 'swasta']))
            ->assertRedirect(route('sumber-bantuan.index'));

        $this->assertDatabaseCount('sumber_bantuan', 2);
    }

    public function test_status_tidak_boleh_dikirim_saat_tambah(): void
    {
        $this->post(route('sumber-bantuan.store'), $this->data(['aktif' => 0]))
            ->assertSessionHasErrors('aktif');
    }

    public function test_ubah_sumber_bantuan_berhasil(): void
    {
        $sumber = $this->buat();

        $this->put(route('sumber-bantuan.update', $sumber), $this->data([
            'kontak' => '081200000000',
            'aktif' => 0,
        ]))->assertRedirect(route('sumber-bantuan.index'));

        $this->assertDatabaseHas('sumber_bantuan', [
            'id' => $sumber->id,
            'kontak' => '081200000000',
            'aktif' => false,
        ]);
    }

    public function test_ubah_tanpa_mengganti_nama_tidak_dianggap_ganda(): void
    {
        $sumber = $this->buat();

        $this->put(route('sumber-bantuan.update', $sumber), $this->data(['aktif' => 1]))
            ->assertSessionHasNoErrors();
    }

    public function test_hapus_sumber_bantuan_yang_belum_dipakai(): void
    {
        $sumber = $this->buat();

        $this->delete(route('sumber-bantuan.destroy', $sumber))
            ->assertRedirect(route('sumber-bantuan.index'));

        $this->assertDatabaseMissing('sumber_bantuan', ['id' => $sumber->id]);
    }

    public function test_sudah_dipakai_false_jika_tabel_penerimaan_belum_ada(): void
    {
        $this->assertFalse($this->buat()->sudahDipakai());
    }
}
