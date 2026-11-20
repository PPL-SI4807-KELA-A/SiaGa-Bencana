<?php

namespace App\Http\Controllers;

use App\Models\StokPosko;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StokPoskoController extends Controller
{
    private const ROLE_ADMIN = 'Admin BPBD';
    private const ROLE_RELAWAN = 'Relawan Posko';

    private const KATEGORI = ['pangan', 'sandang', 'obat-obatan', 'perlengkapan bayi', 'kebersihan'];

    public function __construct(private StokService $stokService)
    {
    }

    private function aktor(Request $request): array
    {
        $user = $request->user();

        if ($user === null) {
            $defaultPoskoId = (int) (StokPosko::query()->value('posko_id') ?? 1);
            $defaultNamaPosko = StokPosko::where('posko_id', $defaultPoskoId)->value('nama_posko')
                ?? "Posko {$defaultPoskoId}";

            // Fallback sementara saat modul auth belum aktif (mode Tamu -> Relawan)
            return [
                self::ROLE_RELAWAN,
                $defaultPoskoId,
                $defaultNamaPosko,
            ];
        }

        $poskoId = data_get($user, 'posko_id');

        return [
            data_get($user, 'role'),
            $poskoId !== null ? (int) $poskoId : null,
            data_get($user, 'posko.nama'),
        ];
    }

    public function index(Request $request)
    {
        [$role, $poskoId] = $this->aktor($request);

        abort_unless(in_array($role, [self::ROLE_ADMIN, self::ROLE_RELAWAN], true), 403);

        $isRelawan = $role === self::ROLE_RELAWAN;
        abort_if($isRelawan && $poskoId === null, 403);

        $base = StokPosko::query();
        if ($isRelawan) {
            $base->where('posko_id', $poskoId);
        } elseif ($request->filled('posko_id')) {
            $base->where('posko_id', (int) $request->input('posko_id'));
        }

        $jumlahMenipis = (clone $base)->menipis()->count();

        $query = clone $base;

        if ($request->filled('q')) {
            $kata = trim($request->input('q'));
            $query->where(function ($q) use ($kata) {
                $q->where('nama_barang', 'like', "%{$kata}%")
                  ->orWhere('kode_barang', 'like', "%{$kata}%");
            });
        }

        if ($request->boolean('menipis')) {
            $query->menipis();
        }

        $items = $query
            ->orderByRaw('(jumlah_stok <= batas_minimum) desc') // yang menipis di atas
            ->orderBy('nama_barang')
            ->paginate(15)
            ->withQueryString();

        $daftarPosko = $isRelawan
            ? collect()
            : StokPosko::query()->select('posko_id', 'nama_posko')->distinct()->orderBy('nama_posko')->get();

        return view('stok_posko.index', [
            'items' => $items,
            'jumlahMenipis' => $jumlahMenipis,
            'daftarPosko' => $daftarPosko,
            'isRelawan' => $isRelawan,
            'kategori' => self::KATEGORI,
        ]);
    }

    public function store(Request $request)
    {
        [$poskoId, $namaPosko] = $this->relawan($request);

        $request->merge(['kode_barang' => strtoupper(trim((string) $request->input('kode_barang')))]);

        $data = $request->validate([
            'kode_barang' => [
                'required', 'string', 'max:50',
                Rule::unique('stok_poskos', 'kode_barang')->where('posko_id', $poskoId),
            ],
            'nama_barang' => ['required', 'string', 'max:255'],
            'kategori' => ['required', Rule::in(self::KATEGORI)],
            'satuan' => ['required', 'string', 'max:30'],
            'jumlah_stok' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'batas_minimum' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ], $this->pesan());

        $this->stokService->tambahBarang($data + [
            'posko_id' => $poskoId, // selalu dari akun/aktor, bukan dari input
            'nama_posko' => $namaPosko,
        ]);

        return redirect()->route('stok-posko.index')
            ->with('success', 'Barang berhasil ditambahkan ke daftar stok.');
    }

    public function update(Request $request, StokPosko $stokPosko)
    {
        $this->pastikanMilikRelawan($request, $stokPosko);

        $data = $request->validate([
            'batas_minimum' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ], $this->pesan());

        $this->stokService->ubahBatasMinimum($stokPosko, (int) $data['batas_minimum']);

        return redirect()->route('stok-posko.index')
            ->with('success', 'Batas minimum berhasil diperbarui.');
    }

    public function destroy(Request $request, StokPosko $stokPosko)
    {
        $this->pastikanMilikRelawan($request, $stokPosko);

        $this->stokService->hapusBarang($stokPosko); // ValidationException bila sudah ada transaksi

        return redirect()->route('stok-posko.index')
            ->with('success', 'Barang dihapus dari daftar stok.');
    }

    /** Hanya Relawan dengan posko. Mengembalikan [posko_id, nama_posko]. */
    private function relawan(Request $request): array
    {
        [$role, $poskoId, $namaDariUser] = $this->aktor($request);

        abort_unless($role === self::ROLE_RELAWAN && $poskoId !== null, 403);

        $nama = $namaDariUser
            ?? StokPosko::where('posko_id', $poskoId)->value('nama_posko')
            ?? "Posko {$poskoId}";

        return [$poskoId, $nama];
    }

    private function pastikanMilikRelawan(Request $request, StokPosko $stokPosko): void
    {
        [$poskoId] = $this->relawan($request);

        abort_unless($stokPosko->posko_id === $poskoId, 403);
    }

    private function pesan(): array
    {
        return [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Barang ini sudah ada di daftar stok posko Anda.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
            'satuan.required' => 'Satuan wajib diisi.',
            'jumlah_stok.required' => 'Jumlah stok wajib diisi.',
            'jumlah_stok.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'jumlah_stok.min' => 'Jumlah stok tidak boleh negatif.',
            'batas_minimum.required' => 'Batas minimum wajib diisi.',
            'batas_minimum.integer' => 'Batas minimum harus berupa bilangan bulat.',
            'batas_minimum.min' => 'Batas minimum tidak boleh negatif.',
        ];
    }
}
