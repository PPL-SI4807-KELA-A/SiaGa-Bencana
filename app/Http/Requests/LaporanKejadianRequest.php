<?php

namespace App\Http\Requests;

use App\Models\KategoriBencana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanKejadianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // halaman publik Warga/Pelapor tanpa login
    }

    // Ketinggian air hanya berlaku untuk kategori Banjir. Untuk kategori lain dikosongkan.
    protected function prepareForValidation(): void
    {
        $id = $this->input('kategori_bencana_id');
        $kategori = is_scalar($id) ? KategoriBencana::find($id) : null;

        if (! $kategori || ! $kategori->isBanjir()) {
            $this->merge(['ketinggian_air' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'kategori_bencana_id' => ['required', Rule::exists('kategori_bencana', 'id')->where('aktif', true)],
            'waktu_kejadian' => ['required', 'date'],
            'desa_kelurahan' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:255'],
            'ketinggian_air' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['required', 'string'],
            'nama_pelapor' => ['nullable', 'string', 'max:100'],
            'kontak_pelapor' => ['required', 'digits_between:8,15'],
            'foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_bencana_id.required' => 'Kategori bencana wajib dipilih.',
            'kategori_bencana_id.exists' => 'Kategori bencana tidak valid.',
            'waktu_kejadian.required' => 'Waktu kejadian wajib diisi.',
            'desa_kelurahan.required' => 'Desa/kelurahan wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'deskripsi.required' => 'Deskripsi kondisi wajib diisi.',
            'kontak_pelapor.required' => 'Kontak pelapor wajib diisi.',
            'kontak_pelapor.digits_between' => 'Kontak pelapor hanya boleh berisi angka, 8 sampai 15 digit.',
            'foto.mimes' => 'Foto harus berformat JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}