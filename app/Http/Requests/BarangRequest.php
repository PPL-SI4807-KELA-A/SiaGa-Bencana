<?php

namespace App\Http\Requests;

use App\Models\Barang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', Rule::in(Barang::KATEGORI)],
            'satuan' => ['required', 'string', 'max:50'],
            'aktif' => $isUpdate ? ['required', 'boolean'] : ['prohibited'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->has('nama') || $validator->errors()->has('kategori')) {
                return;
            }

            $nama = trim((string) $this->input('nama'));
            $namaNormal = mb_strtolower($nama, 'UTF-8');
            $kategori = (string) $this->input('kategori');

            $query = DB::table('barang')->where('kategori', $kategori);
            $barangRoute = $this->route('barang');
            $barangId = $barangRoute instanceof Barang ? $barangRoute->getKey() : $barangRoute;

            if ($barangId !== null) {
                $query->where('id', '<>', $barangId);
            }

            $duplikat = $query->pluck('nama')->contains(
                fn (string $namaTersimpan): bool => mb_strtolower(trim($namaTersimpan), 'UTF-8') === $namaNormal,
            );

            if ($duplikat) {
                $validator->errors()->add('nama', 'Nama barang sudah digunakan dalam kategori ini.');
            }
        });
    }
}
