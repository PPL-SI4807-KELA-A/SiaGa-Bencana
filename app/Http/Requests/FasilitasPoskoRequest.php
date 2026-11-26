<?php

namespace App\Http\Requests;

use App\Models\FasilitasPosko;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FasilitasPoskoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user?->role === 'Relawan Posko' && ! $user->posko_id) {
            abort(403, 'Akun Anda belum terhubung ke posko.');
        }

        if ($user?->role !== 'Relawan Posko') {
            return false;
        }

        $fasilitas = $this->route('fasilitas_posko');
        if ($fasilitas instanceof FasilitasPosko) {
            abort_unless((int) $fasilitas->posko_id === (int) $user->posko_id, 404);
        }

        return true;
    }

    public function rules(): array
    {
        $poskoId = (int) $this->user()->posko_id;

        return [
            'jenis_fasilitas' => [
                'required',
                'string',
                'max:100',
                Rule::unique('fasilitas_posko')
                    ->where('posko_id', $poskoId)
                    ->ignore($this->route('fasilitas_posko')),
            ],
            'jumlah' => ['required', 'integer', 'min:1', 'max:10000'],
            'kondisi' => ['required', Rule::in(FasilitasPosko::KONDISI)],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_fasilitas.required' => 'Jenis fasilitas wajib diisi.',
            'jenis_fasilitas.unique' => 'Jenis fasilitas ini sudah tercatat di posko Anda.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa bilangan bulat.',
            'jumlah.min' => 'Jumlah harus lebih dari nol.',
            'jumlah.max' => 'Jumlah maksimal 10000.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'kondisi.in' => 'Kondisi tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ];
    }
}
