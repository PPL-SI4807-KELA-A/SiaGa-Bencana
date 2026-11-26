<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\StokPosko;
use App\Models\User;
use App\Services\StokService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StokPoskoTest extends TestCase
{
    use RefreshDatabase;

    private int $nomorEmailTest = 0;

    private function emailTest(string $prefix): string
    {
        return $prefix . (++$this->nomorEmailTest) . '@test.local';
    }

    private function relawan(int $poskoId = 1, string $namaPosko = 'Posko Utama'): User
    {
        $user = new User();
        $user->name = "Relawan Posko {$poskoId}";
        $user->email = $this->emailTest("relawan{$poskoId}");
        $user->password = bcrypt('password');
        $user->role = 'Relawan Posko';
        $user->posko_id = $poskoId;
        $user->setRelation('posko', (object) ['id' => $poskoId, 'nama' => $namaPosko]);
        $user->save();

        return $user;
    }

    private function admin(): User
    {
        $user = new User();
        $user->name = 'Admin BPBD';
        $user->email = $this->emailTest('admin');
        $user->password = bcrypt('password');
        $user->role = 'Admin BPBD';
        $user->save();

        return $user;
    }

    private function buatStok(array $override = []): StokPosko
    {
        return StokPosko::create($override + [
            'posko_id' => 1,
            'nama_posko' => 'Posko Utama',
            'kode_barang' => 'BRG-001',
            'nama_barang' => 'Beras Medium',
            'kategori' => 'pangan',
            'satuan' => 'karung',
            'jumlah_stok' => 50,
            'batas_minimum' => 20,
            'memiliki_transaksi' => false,
        ]);
    }

    private function buatBarang(array $override = []): Barang
    {
        return Barang::create($override + [
            'nama' => 'Beras Medium',
            'kategori' => 'pangan',
            'satuan' => 'karung',
            'aktif' => true,
        ]);
    }

    public function test_relawan_hanya_melihat_stok_poskonya_sendiri(): void
    {
        $this->buatStok(['posko_id' => 1, 'nama_posko' => 'Posko Utama', 'kode_barang' => 'BRG-001', 'nama_barang' => 'Beras Posko 1']);
        $this->buatStok(['posko_id' => 2, 'nama_posko' => 'Posko Utara', 'kode_barang' => 'BRG-002', 'nama_barang' => 'Selimut Posko 2']);

        $this->actingAs($this->relawan(1))
            ->get(route('stok-posko.index'))
            ->assertOk()
            ->assertSee('Beras Posko 1')
            ->assertDontSee('Selimut Posko 2');
    }

    public function test_relawan_sees_staff_layout_and_add_stock_button(): void
    {
        $this->buatBarang();

        $this->actingAs($this->relawan(1))
            ->get(route('stok-posko.index'))
            ->assertOk()
            ->assertSee('Keluar')
            ->assertSee('Tambah Barang ke Stok')
            ->assertSee('Beras Medium');
    }

    public function test_admin_melihat_semua_posko_dan_bisa_filter_posko(): void
    {
        $this->buatStok(['posko_id' => 1, 'nama_posko' => 'Posko Utama', 'kode_barang' => 'BRG-001', 'nama_barang' => 'Beras Utama']);
        $this->buatStok(['posko_id' => 2, 'nama_posko' => 'Posko Utara', 'kode_barang' => 'BRG-002', 'nama_barang' => 'Selimut Utara']);

        $this->actingAs($this->admin())
            ->get(route('stok-posko.index'))
            ->assertOk()
            ->assertSee('Beras Utama')
            ->assertSee('Selimut Utara');

        $this->actingAs($this->admin())
            ->get(route('stok-posko.index', ['posko_id' => 2]))
            ->assertOk()
            ->assertSee('Selimut Utara')
            ->assertDontSee('Beras Utama');
    }

    public function test_admin_tidak_bisa_menambah_mengubah_atau_menghapus_stok(): void
    {
        $stok = $this->buatStok();

        $this->actingAs($this->admin())
            ->post(route('stok-posko.store'), [
                'kode_barang' => 'BRG-999',
                'nama_barang' => 'Air',
                'kategori' => 'pangan',
                'satuan' => 'karton',
                'jumlah_stok' => 10,
                'batas_minimum' => 5,
            ])
            ->assertForbidden();

        $this->actingAs($this->admin())
            ->patch(route('stok-posko.update', $stok), ['batas_minimum' => 30])
            ->assertForbidden();

        $this->actingAs($this->admin())
            ->delete(route('stok-posko.destroy', $stok))
            ->assertForbidden();
    }

    public function test_relawan_bisa_tambah_barang_dan_memicu_peringatan_jika_menipis(): void
    {
        $barang = $this->buatBarang();

        $this->actingAs($this->relawan(1))
            ->post(route('stok-posko.store'), [
                'barang_id' => $barang->id,
                'jumlah_stok' => 10,
                'batas_minimum' => 15,
            ])
            ->assertRedirect(route('stok-posko.index'));

        $this->assertDatabaseHas('stok_poskos', [
            'posko_id' => 1,
            'barang_id' => $barang->id,
            'kode_barang' => null,
            'nama_barang' => 'Beras Medium',
            'kategori' => 'pangan',
            'satuan' => 'karung',
            'jumlah_stok' => 10,
            'batas_minimum' => 15,
        ]);

        $this->actingAs($this->relawan(1))
            ->get(route('stok-posko.index'))
            ->assertOk()
            ->assertSee('Di bawah minimum');
    }

    public function test_relawan_tidak_bisa_memilih_barang_nonaktif(): void
    {
        $barangNonaktif = $this->buatBarang(['aktif' => false]);

        $this->actingAs($this->relawan(1))
            ->post(route('stok-posko.store'), [
                'barang_id' => $barangNonaktif->id,
                'jumlah_stok' => 10,
                'batas_minimum' => 5,
            ])
            ->assertSessionHasErrors('barang_id');

        $this->assertDatabaseMissing('stok_poskos', ['barang_id' => $barangNonaktif->id]);
    }

    public function test_relawan_tidak_bisa_tambah_barang_duplikat_di_posko_yang_sama(): void
    {
        $barang = $this->buatBarang();
        $this->buatStok(['posko_id' => 1, 'barang_id' => $barang->id, 'kode_barang' => null]);

        $this->actingAs($this->relawan(1))
            ->post(route('stok-posko.store'), [
                'barang_id' => $barang->id,
                'jumlah_stok' => 20,
                'batas_minimum' => 5,
            ])
            ->assertSessionHasErrors('barang_id');
    }

    public function test_relawan_bisa_mengubah_batas_minimum(): void
    {
        $stok = $this->buatStok(['posko_id' => 1, 'jumlah_stok' => 50, 'batas_minimum' => 20]);

        $this->actingAs($this->relawan(1))
            ->patch(route('stok-posko.update', $stok), ['batas_minimum' => 60])
            ->assertRedirect(route('stok-posko.index'));

        $this->assertDatabaseHas('stok_poskos', [
            'id' => $stok->id,
            'batas_minimum' => 60,
        ]);
    }

    public function test_menghapus_barang_yang_sudah_memiliki_transaksi_ditolak(): void
    {
        $stok = $this->buatStok(['posko_id' => 1, 'memiliki_transaksi' => true]);

        $this->actingAs($this->relawan(1))
            ->delete(route('stok-posko.destroy', $stok))
            ->assertSessionHasErrors('hapus');

        $this->assertDatabaseHas('stok_poskos', ['id' => $stok->id]);
    }

    public function test_relawan_bisa_menghapus_barang_yang_belum_memiliki_transaksi(): void
    {
        $stok = $this->buatStok(['posko_id' => 1, 'memiliki_transaksi' => false]);

        $this->actingAs($this->relawan(1))
            ->delete(route('stok-posko.destroy', $stok))
            ->assertRedirect(route('stok-posko.index'));

        $this->assertDatabaseMissing('stok_poskos', ['id' => $stok->id]);
    }

    public function test_nilai_negatif_ditolak_di_service(): void
    {
        $service = app(StokService::class);

        $this->expectException(ValidationException::class);

        $service->tambahBarang([
            'posko_id' => 1,
            'nama_posko' => 'Posko Utama',
            'kode_barang' => 'BRG-NEG',
            'nama_barang' => 'Minus',
            'kategori' => 'pangan',
            'satuan' => 'pcs',
            'jumlah_stok' => -1,
            'batas_minimum' => 0,
        ]);
    }
}
