<?php

namespace Tests\Feature;

use App\Models\KategoriBencana;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriBencanaTest extends TestCase
{
    use RefreshDatabase;

    // Admin BPBD (peran bawaan factory)
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_halaman_index_menampilkan_data(): void
    {
        KategoriBencana::create(['nama' => 'Banjir', 'aktif' => true]);

        $this->get(route('kategori-bencana.index'))
            ->assertOk()
            ->assertSee('Banjir');
    }

    public function test_tambah_kategori_berhasil(): void
    {
        $this->post(route('kategori-bencana.store'), ['nama' => 'Banjir', 'aktif' => 1])
            ->assertRedirect(route('kategori-bencana.index'));

        $this->assertDatabaseHas('kategori_bencana', ['nama' => 'Banjir', 'aktif' => true]);
    }

    public function test_nama_wajib_diisi(): void
    {
        $this->post(route('kategori-bencana.store'), ['nama' => ''])
            ->assertSessionHasErrors('nama');
    }

    public function test_nama_ganda_ditolak(): void
    {
        KategoriBencana::create(['nama' => 'Banjir', 'aktif' => true]);

        $this->post(route('kategori-bencana.store'), ['nama' => 'Banjir'])
            ->assertSessionHasErrors('nama');
    }

    public function test_ubah_kategori_berhasil(): void
    {
        $k = KategoriBencana::create(['nama' => 'Banjir', 'aktif' => true]);

        $this->put(route('kategori-bencana.update', $k), ['nama' => 'Longsor', 'aktif' => 1])
            ->assertRedirect(route('kategori-bencana.index'));

        $this->assertDatabaseHas('kategori_bencana', ['id' => $k->id, 'nama' => 'Longsor']);
    }

    public function test_ubah_tanpa_mengganti_nama_tidak_dianggap_ganda(): void
    {
        $k = KategoriBencana::create(['nama' => 'Banjir', 'aktif' => true]);

        $this->put(route('kategori-bencana.update', $k), [
            'nama' => 'Banjir',
            'keterangan' => 'Luapan sungai',
            'aktif' => 1,
        ])->assertSessionHasNoErrors();
    }

    public function test_hapus_kategori_yang_belum_dipakai(): void
    {
        $k = KategoriBencana::create(['nama' => 'Angin Kencang', 'aktif' => true]);

        $this->delete(route('kategori-bencana.destroy', $k))
            ->assertRedirect(route('kategori-bencana.index'));

        $this->assertDatabaseMissing('kategori_bencana', ['id' => $k->id]);
    }

    public function test_hanya_kategori_aktif_untuk_formulir(): void
    {
        KategoriBencana::create(['nama' => 'Banjir', 'aktif' => true]);
        KategoriBencana::create(['nama' => 'Lama', 'aktif' => false]);

        $this->assertSame(['Banjir'], KategoriBencana::aktif()->pluck('nama')->all());
    }

    public function test_is_banjir_tidak_peka_huruf_besar_kecil(): void
    {
        $this->assertTrue((new KategoriBencana(['nama' => ' banjir ']))->isBanjir());
        $this->assertFalse((new KategoriBencana(['nama' => 'Tanah Longsor']))->isBanjir());
    }

    public function test_pengunjung_tanpa_login_tidak_bisa_membuka_halaman_admin(): void
    {
        auth()->logout();

        $this->get(route('kategori-bencana.index'))->assertRedirect();
    }
}