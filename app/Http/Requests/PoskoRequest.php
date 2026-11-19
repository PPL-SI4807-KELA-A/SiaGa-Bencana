<?php

namespace App\Http\Requests;

use App\Models\Posko;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PoskoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kecamatan = $this->input('kecamatan');
        $desa = Posko::DESA_PER_KECAMATAN[$kecamatan] ?? [];

        return [
            'nama_posko' => [
                'required', 'string', 'max:150',
                Rule::unique('posko', 'nama_posko')->ignore($this->route('posko')),
            ],
            'alamat' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', Rule::in(Posko::KECAMATAN)],
            'desa_kelurahan' => ['required', 'string', 'max:100', Rule::in($desa)],
            'penanggung_jawab' => ['required', 'string', 'max:100'],
            'kontak_penanggung_jawab' => ['nullable', 'regex:/^(\+62|0)[0-9]{9,13}$/'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:10000'],
            'jumlah_terisi' => ['required', 'integer', 'min:0', 'lte:kapasitas'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_posko.required' => 'Nama posko wajib diisi.',
            'nama_posko.max' => 'Nama posko maksimal 150 karakter.',
            'nama_posko.unique' => 'Nama posko sudah digunakan.',
            'alamat.required' => 'Alamat posko wajib diisi.',
            'alamat.max' => 'Alamat posko maksimal 255 karakter.',
            'kecamatan.required' => 'Kecamatan wajib dipilih.',
            'kecamatan.in' => 'Kecamatan tidak valid.',
            'desa_kelurahan.required' => 'Desa/kelurahan wajib dipilih.',
            'desa_kelurahan.in' => 'Desa/kelurahan tidak sesuai dengan kecamatan.',
            'desa_kelurahan.max' => 'Desa/kelurahan maksimal 100 karakter.',
            'penanggung_jawab.required' => 'Penanggung jawab wajib diisi.',
            'penanggung_jawab.max' => 'Nama penanggung jawab maksimal 100 karakter.',
            'kontak_penanggung_jawab.regex' => 'Format nomor kontak tidak valid.',
            'kapasitas.required' => 'Kapasitas posko wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa bilangan bulat.',
            'kapasitas.min' => 'Kapasitas harus lebih dari nol.',
            'kapasitas.max' => 'Kapasitas maksimal 10000.',
            'jumlah_terisi.required' => 'Jumlah terisi wajib diisi.',
            'jumlah_terisi.integer' => 'Jumlah terisi harus berupa bilangan bulat.',
            'jumlah_terisi.min' => 'Jumlah terisi tidak boleh kurang dari nol.',
            'jumlah_terisi.lte' => 'Jumlah terisi tidak boleh melebihi kapasitas.',
        ];
    }
}
