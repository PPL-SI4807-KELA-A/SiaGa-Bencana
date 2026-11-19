<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanKejadianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // halaman publik Warga/Pelapor tanpa login
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
            'kontak_pelapor' => ['required', 'string', 'max:20'],
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
            'foto.mimes' => 'Foto harus berformat JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}