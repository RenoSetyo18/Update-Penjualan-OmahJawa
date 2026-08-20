@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Jenis Menu</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('jenis.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Jenis Menu Baru</h3>
        </div>
        <form action="{{ route('jenis.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="nama_jenis">Nama Jenis <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_jenis') is-invalid @enderror" id="nama_jenis"
                        name="nama_jenis" placeholder="Contoh: Makanan, Minuman, Cemilan" value="{{ old('nama_jenis') }}" required>
                    @error('nama_jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Masukkan nama kategori menu (Makanan, Minuman, Cemilan, dll)</small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                        name="deskripsi" rows="3" placeholder="Deskripsi jenis menu (opsional)">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('jenis.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
@endsection
