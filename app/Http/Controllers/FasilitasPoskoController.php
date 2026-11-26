<?php

namespace App\Http\Controllers;

use App\Http\Requests\FasilitasPoskoRequest;
use App\Models\FasilitasPosko;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FasilitasPoskoController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $admin = $user->isAdminBpbd();
        $poskoIdRelawan = $admin ? null : $this->poskoRelawan($user);

        $dasar = FasilitasPosko::query()
            ->select('fasilitas_posko.*')
            ->when($poskoIdRelawan !== null, fn ($query) => $query->where('posko_id', $poskoIdRelawan));
        $jumlahJenis = (clone $dasar)->count();
        $jumlahRusakBerat = (clone $dasar)->where('kondisi', 'Rusak Berat')->count();

        $filterKondisi = (string) $request->query('kondisi', '');
        $filterPosko = null;
        if ($admin) {
            $nilaiPosko = filter_var($request->query('posko_id'), FILTER_VALIDATE_INT);
            $filterPosko = $nilaiPosko === false ? null : $nilaiPosko;
        }

        $query = clone $dasar;
        $query->when($filterPosko !== null, fn ($builder) => $builder->where('fasilitas_posko.posko_id', $filterPosko));
        $query->when(in_array($filterKondisi, FasilitasPosko::KONDISI, true), fn ($builder) => $builder->where('fasilitas_posko.kondisi', $filterKondisi));

        $daftarPosko = collect();
        if ($admin && Schema::hasTable('posko')) {
            $daftarPosko = DB::table('posko')->orderBy('nama_posko')->get(['id', 'nama_posko']);
            $query->leftJoin('posko', 'posko.id', '=', 'fasilitas_posko.posko_id')
                ->addSelect('posko.nama_posko');
        } elseif ($admin) {
            $daftarPosko = (clone $dasar)
                ->select('posko_id')
                ->distinct()
                ->orderBy('posko_id')
                ->get()
                ->map(fn ($item) => (object) [
                    'id' => $item->posko_id,
                    'nama_posko' => 'Posko #'.$item->posko_id,
                ]);
        }

        $fasilitas = $query->orderBy('fasilitas_posko.jenis_fasilitas')
            ->paginate(10)
            ->withQueryString();

        return view('fasilitas_posko.index', [
            'fasilitas' => $fasilitas,
            'admin' => $admin,
            'jumlahJenis' => $jumlahJenis,
            'jumlahRusakBerat' => $jumlahRusakBerat,
            'daftarPosko' => $daftarPosko,
            'filterPosko' => $filterPosko,
            'filterKondisi' => $filterKondisi,
            'kondisiList' => FasilitasPosko::KONDISI,
        ]);
    }

    public function create(): View
    {
        $this->poskoRelawan(Auth::user());

        return view('fasilitas_posko.create', [
            'jenisSaran' => FasilitasPosko::SARAN_JENIS,
        ]);
    }

    public function store(FasilitasPoskoRequest $request): RedirectResponse
    {
        $user = $request->user();
        FasilitasPosko::create([
            ...$request->validated(),
            'posko_id' => $this->poskoRelawan($user),
            'dicatat_oleh' => $user->id,
        ]);

        return redirect()->route('fasilitas-posko.index')
            ->with('success', 'Fasilitas posko berhasil ditambahkan.');
    }

    public function edit(FasilitasPosko $fasilitas_posko): View
    {
        $this->pastikanMilikRelawan($fasilitas_posko);

        return view('fasilitas_posko.edit', [
            'fasilitasPosko' => $fasilitas_posko,
            'jenisSaran' => FasilitasPosko::SARAN_JENIS,
        ]);
    }

    public function update(FasilitasPoskoRequest $request, FasilitasPosko $fasilitas_posko): RedirectResponse
    {
        $this->pastikanMilikRelawan($fasilitas_posko);
        $user = $request->user();

        $fasilitas_posko->update([
            ...$request->validated(),
            'posko_id' => $this->poskoRelawan($user),
            'dicatat_oleh' => $user->id,
        ]);

        return redirect()->route('fasilitas-posko.index')
            ->with('success', 'Fasilitas posko berhasil diperbarui.');
    }

    public function destroy(FasilitasPosko $fasilitas_posko): RedirectResponse
    {
        $this->pastikanMilikRelawan($fasilitas_posko);
        $fasilitas_posko->delete();

        return redirect()->route('fasilitas-posko.index')
            ->with('success', 'Fasilitas posko berhasil dihapus.');
    }

    private function poskoRelawan(User $user): int
    {
        abort_if($user->role === 'Relawan Posko' && ! $user->posko_id, 403, 'Akun Anda belum terhubung ke posko.');
        abort_unless($user->role === 'Relawan Posko' && $user->posko_id, 403);

        return (int) $user->posko_id;
    }

    private function pastikanMilikRelawan(FasilitasPosko $fasilitas): void
    {
        $poskoId = $this->poskoRelawan(Auth::user());
        abort_unless((int) $fasilitas->posko_id === $poskoId, 404);
    }
}
