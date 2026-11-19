<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriBencanaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['aktif' => $this->boolean('aktif')]);
    }

    public function rules(): array
    {
        $id = $this->route('kategori_bencana')?->id;

        return [
            'nama' => ['required', 'string', 'max:50',
                Rule::unique('kategori_bencana', 'nama')->ignore($id)],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'aktif' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah ada.',
        ];
    }
}