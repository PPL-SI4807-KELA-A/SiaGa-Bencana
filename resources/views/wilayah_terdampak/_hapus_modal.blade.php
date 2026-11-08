<div class="modal fade" id="hapusWilayah{{ $wilayahTerdampak->id }}" tabindex="-1" aria-labelledby="judulHapusWilayah{{ $wilayahTerdampak->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="judulHapusWilayah{{ $wilayahTerdampak->id }}">Hapus Data Wilayah</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Hapus data <strong>{{ $wilayahTerdampak->desa_kelurahan }}</strong> untuk kejadian tanggal
                <strong>{{ $wilayahTerdampak->tanggal_kejadian->locale('id')->translatedFormat('j F Y') }}</strong>?
                Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('wilayah-terdampak.destroy', $wilayahTerdampak) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
