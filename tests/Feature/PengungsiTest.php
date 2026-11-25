<?php

namespace Tests\Feature;

use App\Models\Pengungsi;
use App\Models\Posko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengungsiTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get(route('pengungsi.index'))->assertRedirect(route('login'));
    }

    public function test_relawan_hanya_melihat_pengungsi_poskonya_sendiri(): void
    {
        $a = $this->posko('Posko Alpha');
        $b = $this->posko('Posko Beta');
        Pengungsi::create($this->data(['posko_id' => $a->id, 'nama' => 'Siti Aminah']));
        Pengungsi::create($this->data(['posko_id' => $b->id, 'nama' => 'Budi Santoso']));

        $this->actingAs($this->relawan($a))
            ->get(route('pengungsi.index'))
            ->assertOk()
            ->assertSee('Siti Aminah')
            ->assertDontSee('Budi Santoso')
            ->assertSee('Posko Alpha');
    }

    public function test_kapasitas_dibaca_dari_tabel_posko(): void
    {
        $posko = $this->posko('Posko Kecil', 10);
        Pengungsi::insert($this->banyak($posko->id, 4));

        $this->actingAs($this->relawan($posko))
            ->get(route('pengungsi.index'))
            ->assertSee('4 dari 10 jiwa terisi');
    }

    public function test_pengungsi_disimpan_di_posko_milik_akun(): void
    {
        $posko = $this->posko('Posko Alpha');

        $this->actingAs($this->relawan($posko))
            ->post(route('pengungsi.store'), $this->data(['nama' => '  Dewi Lestari  ']))
            ->assertRedirect(route('pengungsi.index'))
            ->assertSessionHas('success', 'Data pengungsi berhasil ditambahkan.');

        $this->assertDatabaseHas('pengungsi', [
            'nama' => 'Dewi Lestari',
            'status' => 'Aktif',
            'posko_id' => $posko->id,
        ]);
    }

    public function test_isian_wajib_dan_nilai_pilihan_divalidasi(): void
    {
        $relawan = $this->relawan($this->posko('Posko Alpha'));

        $this->actingAs($relawan)->from(route('pengungsi.index'))
            ->post(route('pengungsi.store'), [])
            ->assertRedirect(route('pengungsi.index'))
            ->assertSessionHasErrors(['nama', 'jenis_kelamin', 'kelompok_usia', 'alamat_asal', 'status']);

        $this->actingAs($relawan)
            ->post(route('pengungsi.store'), $this->data(['kelompok_usia' => 'Remaja']))
            ->assertSessionHasErrors('kelompok_usia');
    }

    public function test_status_dirujuk_diganti_menjadi_dirujuk_ke_fasilitas_kesehatan(): void
    {
        $relawan = $this->relawan($this->posko('Posko Alpha'));

        $this->actingAs($relawan)
            ->post(route('pengungsi.store'), $this->data(['status' => 'Dirujuk']))
            ->assertSessionHasErrors('status');

        $this->actingAs($relawan)
            ->post(route('pengungsi.store'), $this->data(['status' => 'Dirujuk ke Fasilitas Kesehatan']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pengungsi', ['status' => 'Dirujuk ke Fasilitas Kesehatan']);
    }

    public function test_nik_opsional_tetapi_harus_16_digit(): void
    {
        $relawan = $this->relawan($this->posko('Posko Alpha'));

        $this->actingAs($relawan)->post(route('pengungsi.store'), $this->data(['nik' => '']))->assertSessionHasNoErrors();
        $this->actingAs($relawan)->post(route('pengungsi.store'), $this->data(['nik' => '12345']))->assertSessionHasErrors('nik');
        $this->actingAs($relawan)->post(route('pengungsi.store'), $this->data(['nik' => '12345678901234ab']))->assertSessionHasErrors('nik');
    }

    public function test_nik_aktif_tidak_boleh_ganda_tetapi_boleh_jika_data_lama_sudah_pulang(): void
    {
        $posko = $this->posko('Posko Alpha');
        $relawan = $this->relawan($posko);
        Pengungsi::create($this->data(['posko_id' => $posko->id, 'nik' => '3204000000000001']));

        $this->actingAs($relawan)
            ->post(route('pengungsi.store'), $this->data(['nik' => '3204000000000001']))
            ->assertSessionHasErrors('nik');

        Pengungsi::query()->update(['status' => 'Kembali ke Rumah']);

        $this->actingAs($relawan)
            ->post(route('pengungsi.store'), $this->data(['nik' => '3204000000000001']))
            ->assertSessionHasNoErrors();
    }

    public function test_pendaftaran_ditolak_jika_posko_penuh(): void
    {
        $posko = $this->posko('Posko Penuh', 3);
        Pengungsi::insert($this->banyak($posko->id, 3));

        $this->actingAs($this->relawan($posko))
            ->post(route('pengungsi.store'), $this->data(['nama' => 'Warga Baru']))
            ->assertSessionHasErrors('kapasitas');

        $this->assertDatabaseMissing('pengungsi', ['nama' => 'Warga Baru']);
    }

    public function test_pendaftaran_ditolak_jika_posko_tidak_beroperasi(): void
    {
        $posko = $this->posko('Posko Tutup');
        $posko->forceFill(['status_operasional' => 'Tidak Beroperasi'])->save();

        $this->actingAs($this->relawan($posko))
            ->post(route('pengungsi.store'), $this->data(['nama' => 'Warga Baru']))
            ->assertSessionHasErrors('kapasitas');
    }

    public function test_ubah_data_dan_status_berhasil(): void
    {
        $posko = $this->posko('Posko Alpha');
        $p = Pengungsi::create($this->data(['posko_id' => $posko->id, 'nik' => '3204000000000001']));

        $this->actingAs($this->relawan($posko))
            ->put(route('pengungsi.update', $p), $this->data([
                'nama' => 'Nama Baru',
                'nik' => '3204000000000001',
                'status' => 'Dirujuk ke Fasilitas Kesehatan',
            ]))
            ->assertRedirect(route('pengungsi.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pengungsi', [
            'id' => $p->id,
            'nama' => 'Nama Baru',
            'status' => 'Dirujuk ke Fasilitas Kesehatan',
        ]);
    }

    public function test_relawan_tidak_bisa_mengubah_atau_menghapus_pengungsi_posko_lain(): void
    {
        $a = $this->posko('Posko Alpha');
        $b = $this->posko('Posko Beta');
        $milikB = Pengungsi::create($this->data(['posko_id' => $b->id, 'nama' => 'Milik Beta']));

        $this->actingAs($this->relawan($a))
            ->put(route('pengungsi.update', $milikB), $this->data(['nama' => 'Diubah']))
            ->assertNotFound();

        $this->actingAs($this->relawan($a))
            ->delete(route('pengungsi.destroy', $milikB))
            ->assertNotFound();

        $this->assertDatabaseHas('pengungsi', ['id' => $milikB->id, 'nama' => 'Milik Beta']);
    }

    public function test_pencarian_dan_filter_status(): void
    {
        $posko = $this->posko('Posko Alpha');
        Pengungsi::create($this->data(['posko_id' => $posko->id, 'nama' => 'Budi Santoso', 'status' => 'Aktif']));
        Pengungsi::create($this->data(['posko_id' => $posko->id, 'nama' => 'Cecep Hidayat', 'status' => 'Kembali ke Rumah']));
        $relawan = $this->relawan($posko);

        $this->actingAs($relawan)->get(route('pengungsi.index', ['q' => 'Budi']))
            ->assertSee('Budi Santoso')->assertDontSee('Cecep Hidayat');

        $this->actingAs($relawan)->get(route('pengungsi.index', ['status' => 'Kembali ke Rumah']))
            ->assertSee('Cecep Hidayat')->assertDontSee('Budi Santoso');
    }

    public function test_hapus_data_berhasil(): void
    {
        $posko = $this->posko('Posko Alpha');
        $p = Pengungsi::create($this->data(['posko_id' => $posko->id]));

        $this->actingAs($this->relawan($posko))
            ->delete(route('pengungsi.destroy', $p))
            ->assertRedirect(route('pengungsi.index'))
            ->assertSessionHas('success', 'Data pengungsi berhasil dihapus.');

        $this->assertDatabaseMissing('pengungsi', ['id' => $p->id]);
    }

    public function test_admin_melihat_pengungsi_semua_posko_tanpa_tombol_kelola(): void
    {
        $a = $this->posko('Posko Alpha');
        $b = $this->posko('Posko Beta');
        Pengungsi::create($this->data(['posko_id' => $a->id, 'nama' => 'Siti Aminah']));
        Pengungsi::create($this->data(['posko_id' => $b->id, 'nama' => 'Budi Santoso']));

        $this->actingAs($this->admin())
            ->get(route('pengungsi.index'))
            ->assertOk()
            ->assertSee('Siti Aminah')
            ->assertSee('Budi Santoso')
            ->assertSee('Posko Alpha')
            ->assertSee('Posko Beta')
            ->assertDontSee('Tambah Pengungsi')
            ->assertDontSee('Ubah Siti Aminah')
            ->assertDontSee('Hapus Siti Aminah');

        $this->actingAs($this->admin())
            ->get(route('pengungsi.index', ['posko' => $b->id]))
            ->assertSee('Budi Santoso')
            ->assertDontSee('Siti Aminah');
    }

    public function test_admin_tidak_boleh_tambah_ubah_atau_hapus(): void
    {
        $posko = $this->posko('Posko Alpha');
        $p = Pengungsi::create($this->data(['posko_id' => $posko->id, 'nama' => 'Siti Aminah']));
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('pengungsi.store'), $this->data(['nama' => 'Baru']))->assertForbidden();
        $this->actingAs($admin)->put(route('pengungsi.update', $p), $this->data(['nama' => 'Diubah']))->assertForbidden();
        $this->actingAs($admin)->delete(route('pengungsi.destroy', $p))->assertForbidden();

        $this->assertDatabaseHas('pengungsi', ['id' => $p->id, 'nama' => 'Siti Aminah']);
        $this->assertDatabaseMissing('pengungsi', ['nama' => 'Baru']);
    }

    private function posko(string $nama, int $kapasitas = 250): Posko
    {
        return Posko::create([
            'nama_posko' => $nama,
            'alamat' => 'Jl. Uji No. 1',
            'desa_kelurahan' => 'Bojongsoang',
            'kecamatan' => 'Bojongsoang',
            'penanggung_jawab' => 'Penanggung Jawab Uji',
            'kapasitas' => $kapasitas,
        ]);
    }

    private function relawan(Posko $posko): User
    {
        return User::factory()->create(['role' => 'Relawan Posko', 'posko_id' => $posko->id]);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'Admin BPBD', 'posko_id' => null]);
    }

    private function banyak(int $poskoId, int $jumlah): array
    {
        $now = now();

        return array_map(
            fn ($i) => $this->data(['posko_id' => $poskoId, 'nama' => "Warga $i", 'created_at' => $now, 'updated_at' => $now]),
            range(1, $jumlah),
        );
    }

    private function data(array $override = []): array
    {
        return array_merge([
            'nama' => 'Warga Uji',
            'jenis_kelamin' => 'Perempuan',
            'kelompok_usia' => 'Dewasa',
            'alamat_asal' => 'Kp. Cijagra RT 03/RW 07, Citeureup',
            'status' => 'Aktif',
        ], $override);
    }
}
