<?php

namespace App\Http\Requests;

use App\Models\Pengungsi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class PengungsiRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin BPBD hanya boleh melihat; hanya Relawan Posko yang boleh mengelola.
        return Pengungsi::bolehKelola($this->user());
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(Pengungsi::JENIS_KELAMIN)],
            'kelompok_usia' => ['required', Rule::in(Pengungsi::KELOMPOK_USIA)],
            'alamat_asal' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'digits:16'],
            'status' => ['required', Rule::in(Pengungsi::STATUS)],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kelompok_usia.required' => 'Kelompok usia wajib dipilih.',
            'alamat_asal.required' => 'Alamat asal wajib diisi.',
            'nik.digits' => 'NIK harus 16 digit angka.',
            'status.required' => 'Status wajib dipilih.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty() || $this->input('status') !== 'Aktif') {
                return;
            }

            $pengungsi = $this->route('pengungsi');
            $pengungsi = $pengungsi instanceof Pengungsi ? $pengungsi : null;
            $poskoId = (int) ($pengungsi?->posko_id ?? $this->user()->posko_id);

            // NIK hanya boleh terdaftar aktif di satu posko.
            $nik = $this->input('nik');
            if ($nik !== null && $nik !== '') {
                $ganda = DB::table('pengungsi')
                    ->where('nik', $nik)
                    ->where('status', 'Aktif')
                    ->when($pengungsi, fn ($q) => $q->where('id', '<>', $pengungsi->id))
                    ->exists();

                if ($ganda) {
                    $validator->errors()->add('nik', 'NIK ini sudah terdaftar aktif di posko.');
                }
            }

            // Pengungsi yang sudah Aktif di posko ini tidak menambah keterisian.
            if ($pengungsi && $pengungsi->status === 'Aktif') {
                return;
            }

            $posko = Pengungsi::infoPosko($poskoId);

            if (! $posko['ditemukan']) {
                $validator->errors()->add('kapasitas', 'Data posko tidak ditemukan.');
            } elseif (! $posko['beroperasi']) {
                $validator->errors()->add('kapasitas', 'Posko sedang tidak beroperasi, pengungsi tidak dapat ditempatkan.');
            } elseif ($posko['sisa'] <= 0) {
                $validator->errors()->add('kapasitas', 'Posko sudah penuh, pengungsi tidak dapat ditempatkan.');
            }
        });
    }
}
