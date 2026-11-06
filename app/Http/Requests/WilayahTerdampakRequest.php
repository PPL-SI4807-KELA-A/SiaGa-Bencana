<?php

namespace App\Http\Requests;

use App\Models\WilayahTerdampak;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WilayahTerdampakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kecamatan = $this->input('kecamatan');
        $desa = WilayahTerdampak::DESA_PER_KECAMATAN[$kecamatan] ?? [];

        return [
            'kecamatan' => ['required', Rule::in(WilayahTerdampak::KECAMATAN)],
            'desa_kelurahan' => [
                'required', 'string', 'max:100', Rule::in($desa),
                Rule::unique('wilayah_terdampak', 'desa_kelurahan')
                    ->where(fn ($query) => $query->where('tanggal_kejadian', $this->input('tanggal_kejadian')))
                    ->ignore($this->route('wilayah_terdampak')),
            ],
            'tanggal_kejadian' => ['required', 'date', 'before_or_equal:today'],
            'jumlah_kk_terdampak' => ['required', 'integer', 'min:1', 'max:100000'],
            'status_penanganan' => ['required', Rule::in(WilayahTerdampak::STATUS_PENANGANAN)],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'kecamatan.required' => 'Kecamatan wajib dipilih.',
            'kecamatan.in' => 'Kecamatan tidak valid.',
            'desa_kelurahan.required' => 'Desa/kelurahan wajib dipilih.',
            'desa_kelurahan.in' => 'Desa/kelurahan tidak sesuai dengan kecamatan.',
            'desa_kelurahan.max' => 'Nama desa/kelurahan maksimal 100 karakter.',
            'desa_kelurahan.unique' => 'Desa/kelurahan ini sudah tercatat untuk tanggal kejadian yang sama.',
            'tanggal_kejadian.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_kejadian.date' => 'Tanggal kejadian harus berupa tanggal yang valid.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh melewati hari ini.',
            'jumlah_kk_terdampak.required' => 'Jumlah KK terdampak wajib diisi.',
            'jumlah_kk_terdampak.integer' => 'Jumlah KK terdampak harus berupa bilangan bulat.',
            'jumlah_kk_terdampak.min' => 'Jumlah KK terdampak minimal 1.',
            'jumlah_kk_terdampak.max' => 'Jumlah KK terdampak maksimal 100000.',
            'status_penanganan.required' => 'Status penanganan wajib dipilih.',
            'status_penanganan.in' => 'Status penanganan tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ];
    }
}
