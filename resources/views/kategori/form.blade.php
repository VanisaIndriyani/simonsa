@extends('layouts.app')

@section('judul', isset($kategori) ? 'Ubah Kategori' : 'Tambah Kategori')
@section('subjudul', isset($kategori) ? $kategori->nama_pelanggaran : 'Poin disalin saat pelanggaran dicatat')

@section('content')
    @php $ubah = isset($kategori); @endphp
    <div class="form-layout">
        <form class="kartu kartu-isi formulir" method="POST" action="{{ $ubah ? route('kategori.update', $kategori) : route('kategori.store') }}">
            @csrf
            @if($ubah) @method('PUT') @endif

            <div class="bagian-form">
                <h3>Identitas</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="kode_pelanggaran">Kode</label>
                        <input class="form-control" id="kode_pelanggaran" name="kode_pelanggaran" value="{{ old('kode_pelanggaran', $kategori->kode_pelanggaran ?? '') }}" required maxlength="20">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label" for="nama_pelanggaran">Nama pelanggaran</label>
                        <input class="form-control" id="nama_pelanggaran" name="nama_pelanggaran" value="{{ old('nama_pelanggaran', $kategori->nama_pelanggaran ?? '') }}" required maxlength="150">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            @foreach(['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $nilai => $label)
                                <option value="{{ $nilai }}" @selected(old('status', $kategori->status ?? 'aktif') === $nilai)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bagian-form">
                <h3>Bobot</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="poin">Poin</label>
                        <input class="form-control" id="poin" type="number" min="1" name="poin" value="{{ old('poin', $kategori->poin ?? 5) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-aksi">
                <button class="btn-hijau" type="submit">Simpan</button>
                <a class="btn-putih" href="{{ route('kategori.index') }}">Batal</a>
            </div>
        </form>

        <aside class="kartu pratinjau">
            <div class="inisial" id="pratinjau-inisial">{{ $ubah ? strtoupper(mb_substr($kategori->kode_pelanggaran, 0, 1)) : 'K' }}</div>
            <strong id="pratinjau-nama">{{ $ubah ? $kategori->nama_pelanggaran : 'Nama pelanggaran' }}</strong>
            <div class="chip-bar justify-content-center">
                <span class="chip" id="pratinjau-kode">{{ $ubah ? $kategori->kode_pelanggaran : 'Kode' }}</span>
                <span class="chip" id="pratinjau-poin">{{ old('poin', $kategori->poin ?? 5) }} poin</span>
            </div>
            <p id="pratinjau-deskripsi">{{ $ubah && $kategori->deskripsi ? $kategori->deskripsi : 'Deskripsi kategori muncul di sini.' }}</p>
        </aside>
    </div>
@endsection

@push('scripts')
<script>
    const isi = (id, teks) => { document.getElementById(id).textContent = teks; };
    const segar = () => {
        const kode = document.getElementById('kode_pelanggaran').value.trim();
        const nama = document.getElementById('nama_pelanggaran').value.trim();
        const deskripsi = document.getElementById('deskripsi').value.trim();
        isi('pratinjau-inisial', (kode || nama || 'K').charAt(0).toUpperCase());
        isi('pratinjau-nama', nama || 'Nama pelanggaran');
        isi('pratinjau-kode', kode || 'Kode');
        isi('pratinjau-poin', (document.getElementById('poin').value || '0') + ' poin');
        isi('pratinjau-deskripsi', deskripsi || 'Deskripsi kategori muncul di sini.');
    };
    ['kode_pelanggaran', 'nama_pelanggaran', 'poin', 'deskripsi'].forEach((id) => {
        document.getElementById(id).addEventListener('input', segar);
    });
</script>
@endpush
