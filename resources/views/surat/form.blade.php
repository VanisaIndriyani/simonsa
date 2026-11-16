@extends('layouts.app')

@section('judul', isset($surat) ? 'Ubah Surat' : 'Terbitkan Surat Peringatan')
@section('subjudul', 'Nomor kosong akan diisi otomatis')

@section('content')
    @php
        $ubah = isset($surat);
        $idSantri = old('id_santri', $surat->id_santri ?? ($pilihan ?? ''));
        $tingkatAwal = old('tingkat_peringatan', $surat->tingkat_peringatan ?? ($tingkat ?? ''));
    @endphp
    <form class="kartu kartu-isi" method="POST" action="{{ $ubah ? route('surat.update', $surat) : route('surat.store') }}">
        @csrf
        @if($ubah) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Santri</label>
                <select class="form-select" name="id_santri" id="pilihSantri" required>
                    <option value="">Pilih santri</option>
                    @foreach($santri as $item)
                        <option value="{{ $item->id_santri }}" data-poin="{{ $item->poinTerkumpul() }}" @selected($idSantri == $item->id_santri)>{{ $item->nama_santri }} · {{ $item->poinTerkumpul() }} poin</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tingkat</label>
                <select class="form-select" name="tingkat_peringatan" required>
                    <option value="">Pilih</option>
                    @foreach(['SP1', 'SP2', 'SP3'] as $item)
                        <option value="{{ $item }}" @selected($tingkatAwal === $item)>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal surat</label>
                <input class="form-control" type="date" name="tanggal_surat" value="{{ old('tanggal_surat', isset($surat) ? $surat->tanggal_surat->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor surat</label>
                <input class="form-control" name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat ?? '') }}" maxlength="50" placeholder="Kosongkan untuk nomor otomatis">
            </div>
            <div class="col-md-3">
                <label class="form-label">Total poin</label>
                <input class="form-control" type="number" name="total_poin" id="totalPoin" value="{{ old('total_poin', $surat->total_poin ?? '') }}" min="0">
            </div>
            <div class="col-12">
                <label class="form-label">Isi surat</label>
                <textarea class="form-control" name="isi_surat" rows="7" placeholder="Kosongkan untuk memakai template">{{ old('isi_surat', $surat->isi_surat ?? '') }}</textarea>
            </div>
        </div>
        <div class="catatan mt-3">Keputusan sanksi akhir tetap dilakukan di luar sistem.</div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn-hijau" type="submit">Simpan</button>
            <a class="btn-putih" href="{{ route('surat.index') }}">Batal</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const pilih = document.getElementById('pilihSantri');
    const total = document.getElementById('totalPoin');
    pilih.addEventListener('change', () => {
        const poin = pilih.selectedOptions[0]?.dataset.poin;
        if (poin !== undefined) total.value = poin;
    });
    if (!total.value && pilih.value) pilih.dispatchEvent(new Event('change'));
</script>
@endpush
