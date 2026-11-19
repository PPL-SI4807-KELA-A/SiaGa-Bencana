<?php

namespace Tests\Feature;

use App\Models\Posko;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PoskoPublikTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_is_accessible_and_lists_active_posko_without_contact_person_data(): void
    {
        $visible = Posko::create($this->validData([
            'nama_posko' => 'Posko Publik Terlihat',
            'penanggung_jawab' => 'Penanggung Rahasia Uji',
            'kontak_penanggung_jawab' => '081299998888',
        ]));
        $inactive = Posko::create($this->validData([
            'nama_posko' => 'Posko Nonaktif Tersembunyi',
        ]));
        $inactive->is_aktif = false;
        $inactive->save();

        $notOperating = Posko::create($this->validData([
            'nama_posko' => 'Posko Tidak Beroperasi Tetap Tampil',
        ]));
        $notOperating->status_operasional = 'Tidak Beroperasi';
        $notOperating->save();

        $this->get(route('posko.publik'))
            ->assertOk()
            ->assertSee($visible->nama_posko)
            ->assertSee('Posko Tidak Beroperasi Tetap Tampil')
            ->assertSee('Tidak Beroperasi')
            ->assertDontSee('Posko Nonaktif Tersembunyi')
            ->assertDontSee('Penanggung Rahasia Uji')
            ->assertDontSee('081299998888')
            ->assertSee('Sedang tidak menerima pengungsi.');
    }

    public function test_public_filter_returns_only_matching_district_and_ignores_invalid_value(): void
    {
        $this->createPublicPosko('Posko Kecamatan Bojongsoang', 'Bojongsoang');
        $this->createPublicPosko('Posko Kecamatan Dayeuhkolot', 'Dayeuhkolot');

        $this->get(route('posko.publik', ['kecamatan' => 'Dayeuhkolot']))
            ->assertOk()
            ->assertViewHas('posko', fn ($posko) => $posko->count() === 1 && $posko->first()->kecamatan === 'Dayeuhkolot');

        $this->get(route('posko.publik', ['kecamatan' => 'Kecamatan Tidak Valid']))
            ->assertOk()
            ->assertViewHas('posko', fn ($posko) => $posko->count() === 2);
    }

    public function test_empty_public_listing_shows_filter_specific_message(): void
    {
        $this->get(route('posko.publik'))
            ->assertOk()
            ->assertSee('Belum ada posko aktif saat ini.');

        $this->get(route('posko.publik', ['kecamatan' => 'Dayeuhkolot']))
            ->assertOk()
            ->assertSee('Tidak ada posko aktif di kecamatan ini.');
    }

    private function createPublicPosko(string $name, string $district): Posko
    {
        $village = $district === 'Bojongsoang' ? 'Bojongsoang' : 'Dayeuhkolot';

        return Posko::create($this->validData([
            'nama_posko' => $name,
            'kecamatan' => $district,
            'desa_kelurahan' => $village,
        ]));
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'nama_posko' => 'Posko Uji Publik',
            'alamat' => 'Alamat Uji Posko',
            'desa_kelurahan' => 'Bojongsoang',
            'kecamatan' => 'Bojongsoang',
            'penanggung_jawab' => 'Nama PIC Internal',
            'kontak_penanggung_jawab' => '081234567890',
            'kapasitas' => 50,
            'jumlah_terisi' => 40,
        ], $overrides);
    }
}
