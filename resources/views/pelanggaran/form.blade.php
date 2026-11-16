@extends('layouts.app')

@section('judul', isset($pelanggaran) ? 'Ubah Pelanggaran' : 'Catat Pelanggaran')
@section('subjudul', 'Poin mengikuti kategori yang dipilih')

@section('content')
    @php $ubah = isset($pelanggaran); @endphp
    <form class="kartu kartu-isi" method="POST" action="{{ $ubah ? route('pelanggaran.update', $pelanggaran) : route('pelanggaran.store') }}">
        @csrf
        @if($ubah) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Santri</label>
                <select class="form-select" name="id_santri" required>
                    <option value="">Pilih santri</option>
                    @foreach($santri as $item)
                        <option value="{{ $item->id_santri }}" @selected(old('id_santri', $pelanggaran->id_santri ?? '') == $item->id_santri)>{{ $item->nis }} · {{ $item->nama_santri }} · {{ $item->kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select class="form-select" name="id_kategori" id="kategori" required>
                    <option value="">Pilih kategori</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item->id_kategori }}" data-poin="{{ $item->poin }}" @selected(old('id_kategori', $pelanggaran->id_kategori ?? '') == $item->id_kategori)>{{ $item->nama_pelanggaran }} ({{ $item->poin }} poin)</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input class="form-control" type="date" name="tanggal_pelanggaran" value="{{ old('tanggal_pelanggaran', isset($pelanggaran) ? $pelanggaran->tanggal_pelanggaran->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Poin</label>
                <input class="form-control" id="tampilPoin" value="{{ $pelanggaran->poin_pelanggaran ?? '-' }}" readonly>
            </div>
            <div class="col-12">
                <label class="form-label">Kronologi</label>
                <textarea class="form-control" name="kronologi" rows="4" required>{{ old('kronologi', $pelanggaran->kronologi ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Tindak lanjut</label>
                <textarea class="form-control" name="tindak_lanjut" rows="3">{{ old('tindak_lanjut', $pelanggaran->tindak_lanjut ?? '') }}</textarea>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn-hijau" type="submit">Simpan</button>
            <a class="btn-putih" href="{{ route('pelanggaran.index') }}">Batal</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const kategori = document.getElementById('kategori');
    const poin = document.getElementById('tampilPoin');
    const isi = () => {
        const opsi = kategori.selectedOptions[0];
        poin.value = opsi?.dataset.poin || '-';
    };
    kategori.addEventListener('change', isi);
    isi();
</script>
@endpush
