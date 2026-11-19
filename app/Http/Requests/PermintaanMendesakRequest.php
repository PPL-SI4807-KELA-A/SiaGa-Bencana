<?php

namespace App\Http\Requests;

use App\Models\PermintaanMendesak;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermintaanMendesakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'jenis_kebutuhan' => is_string($this->input('jenis_kebutuhan')) ? trim($this->input('jenis_kebutuhan')) : $this->input('jenis_kebutuhan'),
            'lokasi' => is_string($this->input('lokasi')) ? trim($this->input('lokasi')) : $this->input('lokasi'),
        ]);
    }

    public function rules(): array
    {
        return [
            'jenis_kebutuhan' => ['required', 'string', 'max:100'],
            'perkiraan_jumlah' => ['required', 'integer', 'min:1'],
            'lokasi' => ['required', 'string', 'max:255'],
            'nomor_kontak' => ['required', 'string', 'regex:/^\+?[0-9]{8,15}$/'],
            'posko' => ['nullable', 'string', Rule::in(PermintaanMendesak::DAFTAR_POSKO)],
            'kode_pelacakan' => ['prohibited'],
            'status' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_kebutuhan.required' => 'Jenis kebutuhan wajib diisi.',
            'jenis_kebutuhan.max' => 'Jenis kebutuhan maksimal 100 karakter.',
            'perkiraan_jumlah.required' => 'Perkiraan jumlah wajib diisi.',
            'perkiraan_jumlah.integer' => 'Perkiraan jumlah harus berupa bilangan bulat.',
            'perkiraan_jumlah.min' => 'Perkiraan jumlah minimal 1.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'lokasi.max' => 'Lokasi maksimal 255 karakter.',
            'nomor_kontak.required' => 'Nomor kontak wajib diisi.',
            'nomor_kontak.regex' => 'Nomor kontak harus berisi 8 sampai 15 digit, dengan tanda + opsional di depan.',
            'posko.in' => 'Posko yang dipilih tidak valid.',
        ];
    }
}
