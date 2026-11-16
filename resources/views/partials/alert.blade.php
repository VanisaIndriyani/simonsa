@if(session('sukses'))
    <div class="alert alert-success">{{ session('sukses') }}</div>
@endif
@if(session('peringatan'))
    <div class="alert alert-warning">
        {{ session('peringatan') }}
        @if(session('tautan_sp'))
            <a href="{{ session('tautan_sp') }}">Terbitkan surat</a>
        @endif
    </div>
@endif
@if(session('gagal'))
    <div class="alert alert-danger">{{ session('gagal') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
