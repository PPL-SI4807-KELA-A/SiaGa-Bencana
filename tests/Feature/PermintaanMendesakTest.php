<?php

namespace Tests\Feature;

use App\Models\PermintaanMendesak;
use App\Models\Posko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermintaanMendesakTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_is_created_with_server_generated_tracking_code(): void
    {
        $response = $this->post(route('permintaan-mendesak.store'), $this->validData());

        $permintaan = PermintaanMendesak::query()->firstOrFail();

        $response->assertRedirect(route('permintaan-mendesak.show', $permintaan))
            ->assertSessionHas('kode_pelacakan', $permintaan->kode_pelacakan);
        $this->assertMatchesRegularExpression('/^PMD-[A-Z0-9]{8}$/', $permintaan->kode_pelacakan);
        $this->assertSame(PermintaanMendesak::STATUS_DIAJUKAN, $permintaan->status);
        $this->assertDatabaseHas('permintaan_mendesak', [
            'kode_pelacakan' => $permintaan->kode_pelacakan,
            'jenis_kebutuhan' => 'Air bersih',
        ]);
    }

    public function test_required_fields_and_contact_format_are_validated(): void
    {
        $this->from(route('permintaan-mendesak.create'))
            ->post(route('permintaan-mendesak.store'), [])
            ->assertRedirect(route('permintaan-mendesak.create'))
            ->assertSessionHasErrors([
                'jenis_kebutuhan', 'perkiraan_jumlah', 'lokasi', 'nomor_kontak',
            ]);

        $this->post(route('permintaan-mendesak.store'), $this->validData([
            'nomor_kontak' => '0812-3456-7890',
        ]))->assertSessionHasErrors('nomor_kontak');
    }

    public function test_create_form_lists_active_posko_in_name_order_and_omits_inactive_posko_without_login(): void
    {
        $poskoZ = $this->buatPosko('Posko Zeta');
        $this->buatPosko('Posko Nonaktif', false);
        $poskoA = $this->buatPosko('Posko Alpha');

        $this->get(route('permintaan-mendesak.create'))
            ->assertOk()
            ->assertSee('Tidak memilih posko')
            ->assertSeeInOrder([$poskoA->nama_posko, $poskoZ->nama_posko])
            ->assertDontSee('Posko Nonaktif');
    }

    public function test_edit_form_keeps_existing_inactive_posko_available(): void
    {
        $this->buatPosko('Posko Lama', false);
        $poskoAktif = $this->buatPosko('Posko Aktif');
        $permintaan = PermintaanMendesak::create($this->validData(['posko' => 'Posko Lama']));

        $this->get(route('permintaan-mendesak.edit', $permintaan))
            ->assertOk()
            ->assertSee($poskoAktif->nama_posko)
            ->assertSee('value="Posko Lama" selected', false);
    }

    public function test_inactive_or_unknown_posko_is_rejected_for_new_request(): void
    {
        $this->buatPosko('Posko Nonaktif', false);
        $this->buatPosko('Posko Aktif');

        $this->from(route('permintaan-mendesak.create'))
            ->post(route('permintaan-mendesak.store'), $this->validData(['posko' => 'Posko Nonaktif']))
            ->assertRedirect(route('permintaan-mendesak.create'))
            ->assertSessionHasErrors('posko');

        $this->post(route('permintaan-mendesak.store'), $this->validData(['posko' => 'Posko Tidak Terdaftar']))
            ->assertSessionHasErrors('posko');
    }

    public function test_tracking_form_redirects_for_valid_code_and_rejects_invalid_code(): void
    {
        $permintaan = PermintaanMendesak::create($this->validData());

        $this->post(route('permintaan-mendesak.proses-lacak'), [
            'kode_pelacakan' => $permintaan->kode_pelacakan,
        ])->assertRedirect(route('permintaan-mendesak.show', $permintaan));

        $this->from(route('permintaan-mendesak.lacak'))
            ->post(route('permintaan-mendesak.proses-lacak'), [
                'kode_pelacakan' => 'KODE-TIDAK-VALID',
            ])
            ->assertRedirect(route('permintaan-mendesak.lacak'))
            ->assertSessionHasErrors('kode_pelacakan');
    }

    public function test_tracking_code_that_does_not_exist_is_rejected(): void
    {
        $this->from(route('permintaan-mendesak.lacak'))
            ->post(route('permintaan-mendesak.proses-lacak'), [
                'kode_pelacakan' => 'PMD-ABCDEFG1',
            ])
            ->assertRedirect(route('permintaan-mendesak.lacak'))
            ->assertSessionHasErrors('kode_pelacakan');
    }

    public function test_request_can_be_updated_while_submitted(): void
    {
        $permintaan = PermintaanMendesak::create($this->validData());

        $this->put(route('permintaan-mendesak.update', $permintaan), $this->validData([
            'jenis_kebutuhan' => 'Air minum',
            'perkiraan_jumlah' => 25,
        ]))
            ->assertRedirect(route('permintaan-mendesak.show', $permintaan))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('permintaan_mendesak', [
            'kode_pelacakan' => $permintaan->kode_pelacakan,
            'jenis_kebutuhan' => 'Air minum',
            'perkiraan_jumlah' => 25,
        ]);
    }

    public function test_existing_inactive_posko_can_be_kept_during_update(): void
    {
        $this->buatPosko('Posko Lama', false);
        $permintaan = PermintaanMendesak::create($this->validData(['posko' => 'Posko Lama']));

        $this->put(route('permintaan-mendesak.update', $permintaan), $this->validData([
            'jenis_kebutuhan' => 'Air minum',
            'posko' => 'Posko Lama',
        ]))
            ->assertRedirect(route('permintaan-mendesak.show', $permintaan))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('permintaan_mendesak', [
            'kode_pelacakan' => $permintaan->kode_pelacakan,
            'posko' => 'Posko Lama',
            'jenis_kebutuhan' => 'Air minum',
        ]);
    }

    public function test_request_can_be_cancelled_while_submitted(): void
    {
        $permintaan = PermintaanMendesak::create($this->validData());

        $this->delete(route('permintaan-mendesak.destroy', $permintaan))
            ->assertRedirect(route('permintaan-mendesak.lacak'))
            ->assertSessionHas('success', 'Permintaan berhasil dibatalkan.');

        $this->assertDatabaseMissing('permintaan_mendesak', [
            'kode_pelacakan' => $permintaan->kode_pelacakan,
        ]);
    }

    public function test_request_cannot_be_updated_or_cancelled_after_status_changes(): void
    {
        $permintaan = PermintaanMendesak::create($this->validData());
        $permintaan->status = 'Diproses';
        $permintaan->save();

        $this->put(route('permintaan-mendesak.update', $permintaan), $this->validData())
            ->assertForbidden();

        $this->delete(route('permintaan-mendesak.destroy', $permintaan))
            ->assertForbidden();

        $this->assertDatabaseHas('permintaan_mendesak', [
            'kode_pelacakan' => $permintaan->kode_pelacakan,
            'status' => 'Diproses',
        ]);
    }

    public function test_staff_index_lists_all_requests(): void
    {
        $this->actingAs(User::factory()->create());

        $pertama = PermintaanMendesak::create($this->validData());
        $kedua = PermintaanMendesak::create($this->validData([
            'jenis_kebutuhan' => 'Selimut',
            'posko' => 'Posko Lembang',
        ]));

        $this->get(route('admin.permintaan-mendesak.index'))
            ->assertOk()
            ->assertSee($pertama->kode_pelacakan)
            ->assertSee($kedua->kode_pelacakan)
            ->assertSee($kedua->jenis_kebutuhan)
            ->assertSee('Posko Lembang');
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'jenis_kebutuhan' => 'Air bersih',
            'perkiraan_jumlah' => 20,
            'lokasi' => 'Kampung Bojongsoang',
            'nomor_kontak' => '+628123456789',
            'posko' => null,
        ], $overrides);
    }

    private function buatPosko(string $nama, bool $aktif = true): Posko
    {
        $posko = Posko::create([
            'nama_posko' => $nama,
            'alamat' => 'Jalan Uji No. 1',
            'desa_kelurahan' => 'Bojongsoang',
            'kecamatan' => 'Bojongsoang',
            'penanggung_jawab' => 'Petugas Uji',
            'kontak_penanggung_jawab' => '081234567890',
            'kapasitas' => 50,
            'jumlah_terisi' => 10,
        ]);

        if (! $aktif) {
            $posko->is_aktif = false;
            $posko->save();
        }

        return $posko->refresh();
    }
}
