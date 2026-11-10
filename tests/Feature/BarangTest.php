<?php

namespace Tests\Feature;

use App\Models\Barang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarangTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_active_and_inactive_barang(): void
    {
        $aktif = Barang::create($this->validData());
        $nonaktif = Barang::create($this->validData([
            'nama' => 'Selimut',
            'kategori' => 'sandang',
            'satuan' => 'pcs',
            'aktif' => false,
        ]));

        $this->get(route('barang.index'))
            ->assertOk()
            ->assertSee($aktif->nama)
            ->assertSee($nonaktif->nama)
            ->assertSee('Aktif')
            ->assertSee('Nonaktif')
            ->assertSee('pcs');
    }

    public function test_create_and_edit_pages_are_available(): void
    {
        $barang = Barang::create($this->validData());

        $this->get(route('barang.create'))->assertOk();
        $this->get(route('barang.edit', $barang))->assertOk()->assertSee($barang->nama);
    }

    public function test_valid_barang_is_saved_with_trimmed_name_and_redirected(): void
    {
        $data = $this->validData(['nama' => '  Beras  ']);

        $this->post(route('barang.store'), $data)
            ->assertRedirect(route('barang.index'))
            ->assertSessionHas('success', 'Data barang berhasil ditambahkan.');

        $this->assertDatabaseHas('barang', [
            'nama' => 'Beras',
            'kategori' => 'pangan',
            'satuan' => 'kg',
            'aktif' => true,
        ]);
    }

    public function test_required_fields_and_category_values_are_validated(): void
    {
        $this->from(route('barang.create'))
            ->post(route('barang.store'), [])
            ->assertRedirect(route('barang.create'))
            ->assertSessionHasErrors(['nama', 'kategori', 'satuan']);

        $this->post(route('barang.store'), $this->validData(['kategori' => 'elektronik']))
            ->assertSessionHasErrors('kategori');
    }

    public function test_name_is_unique_within_category_ignoring_case_and_outer_spaces(): void
    {
        Barang::create($this->validData(['nama' => 'Beras']));

        $this->from(route('barang.create'))
            ->post(route('barang.store'), $this->validData(['nama' => ' bErAs ']))
            ->assertRedirect(route('barang.create'))
            ->assertSessionHasErrors('nama');

        $this->post(route('barang.store'), $this->validData([
            'nama' => 'Beras',
            'kategori' => 'sandang',
        ]))->assertRedirect(route('barang.index'));
    }

    public function test_unit_is_limited_to_fifty_characters(): void
    {
        $this->post(route('barang.store'), $this->validData(['satuan' => str_repeat('a', 51)]))
            ->assertSessionHasErrors('satuan');
    }

    public function test_update_allows_current_name_and_updates_details_and_status(): void
    {
        $barang = Barang::create($this->validData());

        $this->put(route('barang.update', $barang), $this->validData([
            'nama' => ' Beras ',
            'satuan' => 'karung',
            'aktif' => '0',
        ]))
            ->assertRedirect(route('barang.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('barang', [
            'id' => $barang->id,
            'nama' => 'Beras',
            'satuan' => 'karung',
            'aktif' => false,
        ]);
    }

    public function test_update_rejects_duplicate_name_in_same_category(): void
    {
        Barang::create($this->validData(['nama' => 'Beras']));
        $barang = Barang::create($this->validData(['nama' => 'Gula']));

        $this->from(route('barang.edit', $barang))
            ->put(route('barang.update', $barang), $this->validData(['nama' => ' BERAS ']))
            ->assertRedirect(route('barang.edit', $barang))
            ->assertSessionHasErrors('nama');
    }

    public function test_destroy_deletes_barang_when_stock_table_is_unavailable(): void
    {
        $barang = Barang::create($this->validData());

        $this->delete(route('barang.destroy', $barang))
            ->assertRedirect(route('barang.index'))
            ->assertSessionHas('success', 'Data barang berhasil dihapus.');

        $this->assertDatabaseMissing('barang', ['id' => $barang->id]);
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'nama' => 'Beras',
            'kategori' => 'pangan',
            'satuan' => 'kg',
        ], $overrides);
    }
}
