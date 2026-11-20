<?php

namespace Tests\Feature;

use App\Models\KategoriBencana;
use App\Models\LaporanKejadian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanKejadianTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function kategori(bool $aktif = true): KategoriBencana
    {
        return KategoriBencana::create(['nama' => 'Banjir ' . uniqid(), 'aktif' => $aktif]);
    }

    private function data(array $override = []): array
    {
        return array_merge([
            'kategori_bencana_id' => $this->kategori()->id,
            'waktu_kejadian' => '2026-11-18 05:00:00',
            'desa_kelurahan' => 'Citeureup',
            'lokasi' => 'Kp. Cijagra RT 03/RW 07',
            'ketinggian_air' => '50-100 cm',
            'deskripsi' => 'Air Sungai Citarum meluap.',
            'nama_pelapor' => 'Dede Suryana',
            'kontak_pelapor' => '081220457781',
        ], $override);
    }

    private function buatLaporan(array $override = []): LaporanKejadian
    {
        return LaporanKejadian::create(array_merge($this->data(), [
            'kode_pelacakan' => 'LAP-2611-0001',
        ], $override));
    }

    public function test_formulir_hanya_menampilkan_kategori_aktif(): void
    {
        KategoriBencana::create(['nama' => 'Banjir', 'aktif' => true]);
        KategoriBencana::create(['nama' => 'Kategori Lama', 'aktif' => false]);

        $this->get(route('laporan-kejadian.publik.create'))
            ->assertOk()
            ->assertSee('Banjir')
            ->assertDontSee('Kategori Lama');
    }

    public function test_kirim_laporan_berhasil_dengan_kode_pelacakan(): void
    {
        $response = $this->post(route('laporan-kejadian.publik.store'), $this->data());

        $laporan = LaporanKejadian::firstOrFail();
        $this->assertMatchesRegularExpression('/^LAP-\d{4}-\d{4}$/', $laporan->kode_pelacakan);
        $this->assertSame('Menunggu Verifikasi', $laporan->status);
        $response->assertRedirect(route('laporan-kejadian.publik.show', $laporan->kode_pelacakan));
    }

    public function test_field_wajib_divalidasi(): void
    {
        $this->post(route('laporan-kejadian.publik.store'), [])
            ->assertSessionHasErrors([
                'kategori_bencana_id', 'waktu_kejadian', 'desa_kelurahan',
                'lokasi', 'deskripsi', 'kontak_pelapor',
            ]);
    }

    public function test_kategori_nonaktif_ditolak(): void
    {
        $nonaktif = $this->kategori(false);

        $this->post(route('laporan-kejadian.publik.store'), $this->data(['kategori_bencana_id' => $nonaktif->id]))
            ->assertSessionHasErrors('kategori_bencana_id');
    }

    public function test_foto_jpg_valid_tersimpan(): void
    {
        Storage::fake('public');

        $this->post(route('laporan-kejadian.publik.store'), $this->data([
            'foto' => UploadedFile::fake()->create('banjir.jpg', 500, 'image/jpeg'),
        ]))->assertSessionHasNoErrors();

        Storage::disk('public')->assertExists(LaporanKejadian::firstOrFail()->foto);
    }

    public function test_foto_bukan_jpg_png_atau_lebih_2mb_ditolak(): void
    {
        Storage::fake('public');

        $this->post(route('laporan-kejadian.publik.store'), $this->data([
            'foto' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]))->assertSessionHasErrors('foto');

        $this->post(route('laporan-kejadian.publik.store'), $this->data([
            'foto' => UploadedFile::fake()->create('besar.jpg', 3000, 'image/jpeg'),
        ]))->assertSessionHasErrors('foto');
    }

    public function test_cek_kode_ditemukan_dan_tidak_ditemukan(): void
    {
        $laporan = $this->buatLaporan();

        $this->get(route('laporan-kejadian.publik.cek', ['kode' => 'lap-2611-0001']))
            ->assertRedirect(route('laporan-kejadian.publik.show', $laporan->kode_pelacakan));

        $this->get(route('laporan-kejadian.publik.cek', ['kode' => 'LAP-0000-0000']))
            ->assertOk()
            ->assertSee('tidak ditemukan');
    }

    public function test_halaman_laporan_menampilkan_status(): void
    {
        $laporan = $this->buatLaporan();

        $this->get(route('laporan-kejadian.publik.show', $laporan->kode_pelacakan))
            ->assertOk()
            ->assertSee('LAP-2611-0001')
            ->assertSee('Menunggu Verifikasi');
    }

    public function test_perbaiki_laporan_selama_belum_diverifikasi(): void
    {
        $laporan = $this->buatLaporan();

        $this->put(route('laporan-kejadian.publik.update', $laporan->kode_pelacakan), $this->data([
            'kategori_bencana_id' => $laporan->kategori_bencana_id,
            'lokasi' => 'Lokasi baru',
        ]))->assertRedirect(route('laporan-kejadian.publik.show', $laporan->kode_pelacakan));

        $this->assertDatabaseHas('laporan_kejadian', ['id' => $laporan->id, 'lokasi' => 'Lokasi baru']);
    }

    public function test_batalkan_laporan_menghapus_data(): void
    {
        $laporan = $this->buatLaporan();

        $this->delete(route('laporan-kejadian.publik.destroy', $laporan->kode_pelacakan))
            ->assertRedirect(route('laporan-kejadian.publik.cek'));

        $this->assertDatabaseMissing('laporan_kejadian', ['id' => $laporan->id]);
    }

    public function test_admin_dapat_melihat_daftar_dan_detail(): void
    {
        $laporan = $this->buatLaporan();

        $this->get(route('laporan-kejadian.index'))
            ->assertOk()
            ->assertSee('LAP-2611-0001');

        $this->get(route('laporan-kejadian.show', $laporan->id))
            ->assertOk()
            ->assertSee('Citeureup');
    }

    public function test_kategori_yang_dipakai_laporan_dinonaktifkan_saat_dihapus(): void
    {
        $laporan = $this->buatLaporan();
        $kategori = $laporan->kategori;

        $this->delete(route('kategori-bencana.destroy', $kategori));

        $this->assertDatabaseHas('kategori_bencana', ['id' => $kategori->id, 'aktif' => false]);
    }
}
