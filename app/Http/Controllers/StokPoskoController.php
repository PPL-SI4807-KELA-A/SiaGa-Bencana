<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokPosko;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StokPoskoController extends Controller
{
    private const ROLE_ADMIN = 'Admin BPBD';
    private const ROLE_RELAWAN = 'Relawan Posko';

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

        $barangAktif = $isRelawan
            ? Barang::query()->where('aktif', true)->orderBy('nama')->get(['id', 'nama', 'kategori', 'satuan'])
            : collect();

        return view('stok_posko.index', [
            'items' => $items,
            'jumlahMenipis' => $jumlahMenipis,
            'daftarPosko' => $daftarPosko,
            'barangAktif' => $barangAktif,
            'isRelawan' => $isRelawan,
        ]);
    }

    public function store(Request $request)
    {
        [$poskoId, $namaPosko] = $this->relawan($request);

        $data = $request->validate([
            'barang_id' => [
                'required', 'integer',
                Rule::exists('barang', 'id')->where('aktif', true),
                Rule::unique('stok_poskos', 'barang_id')->where('posko_id', $poskoId),
            ],
            'jumlah_stok' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'batas_minimum' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ], $this->pesan());

        $barang = Barang::query()
            ->where('aktif', true)
            ->findOrFail((int) $data['barang_id']);

        $this->stokService->tambahBarang([
            'barang_id' => $barang->id,
            'kode_barang' => null,
            'nama_barang' => $barang->nama,
            'kategori' => $barang->kategori,
            'satuan' => $barang->satuan,
            'jumlah_stok' => (int) $data['jumlah_stok'],
            'batas_minimum' => (int) $data['batas_minimum'],
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
            'barang_id.required' => 'Barang wajib dipilih dari Data Barang.',
            'barang_id.exists' => 'Barang tidak tersedia atau sudah tidak aktif.',
            'barang_id.unique' => 'Barang ini sudah ada di daftar stok posko Anda.',
            'jumlah_stok.required' => 'Jumlah stok wajib diisi.',
            'jumlah_stok.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'jumlah_stok.min' => 'Jumlah stok tidak boleh negatif.',
            'batas_minimum.required' => 'Batas minimum wajib diisi.',
            'batas_minimum.integer' => 'Batas minimum harus berupa bilangan bulat.',
            'batas_minimum.min' => 'Batas minimum tidak boleh negatif.',
        ];
    }
}
