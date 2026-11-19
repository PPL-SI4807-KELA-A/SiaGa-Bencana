<?php

namespace App\Http\Requests;

use App\Models\SumberBantuan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SumberBantuanRequest extends FormRequest
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
            'jenis' => ['required', 'string', Rule::in(SumberBantuan::JENIS)],
            'kontak' => ['required', 'string', 'max:255'],
            'aktif' => $isUpdate ? ['required', 'boolean'] : ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama sumber bantuan wajib diisi.',
            'jenis.required' => 'Jenis sumber bantuan wajib dipilih.',
            'jenis.in' => 'Jenis sumber bantuan tidak valid.',
            'kontak.required' => 'Kontak wajib diisi.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->has('nama') || $validator->errors()->has('jenis')) {
                return;
            }

            $nama = trim((string) $this->input('nama'));
            $namaNormal = mb_strtolower($nama, 'UTF-8');
            $jenis = (string) $this->input('jenis');

            $query = DB::table('sumber_bantuan')->where('jenis', $jenis);
            $sumberRoute = $this->route('sumber_bantuan');
            $sumberId = $sumberRoute instanceof SumberBantuan ? $sumberRoute->getKey() : $sumberRoute;

            if ($sumberId !== null) {
                $query->where('id', '<>', $sumberId);
            }

            $duplikat = $query->pluck('nama')->contains(
                fn (string $namaTersimpan): bool => mb_strtolower(trim($namaTersimpan), 'UTF-8') === $namaNormal,
            );

            if ($duplikat) {
                $validator->errors()->add('nama', 'Nama sumber bantuan sudah digunakan untuk jenis ini.');
            }
        });
    }
}