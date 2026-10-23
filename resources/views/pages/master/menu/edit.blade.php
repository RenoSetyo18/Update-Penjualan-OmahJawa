@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Menu</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('master-menu.index') }}" class="btn btn-secondary btn-back-action">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Menu</h3>
        </div>
        <form action="{{ route('master-menu.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama', $menu->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jenis_id">Jenis Menu <span class="text-danger">*</span></label>
                            <select class="form-control @error('jenis_id') is-invalid @enderror" id="jenis_id" name="jenis_id" required>
                                <option value="">-- Pilih Jenis Menu --</option>
                                @foreach ($jenis_list as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_id', $menu->jenis_id) == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga"
                                name="harga" step="1000" min="0" value="{{ old('harga', $menu->harga) }}" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estimasi_waktu">Estimasi Waktu Pengerjaan <span class="text-danger">*</span></label>
                            <select class="form-control @error('estimasi_waktu') is-invalid @enderror" id="estimasi_waktu" name="estimasi_waktu" required>
                                <option value="">-- Pilih Estimasi Waktu --</option>
                                <option value="cepat" {{ old('estimasi_waktu', $menu->estimasi_waktu) == 'cepat' ? 'selected' : '' }}>Cepat (5 menit)</option>
                                <option value="sedang" {{ old('estimasi_waktu', $menu->estimasi_waktu) == 'sedang' ? 'selected' : '' }}>Sedang (10 menit)</option>
                                <option value="lama" {{ old('estimasi_waktu', $menu->estimasi_waktu) == 'lama' ? 'selected' : '' }}>Lama (20 menit)</option>
                            </select>
                            @error('estimasi_waktu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="active">Status Menu <span class="text-danger">*</span></label>
                            <select class="form-control @error('active') is-invalid @enderror" id="active" name="active" required>
                                <option value="1" {{ old('active', $menu->active) == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('active', $menu->active) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('master-menu.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
@endsection
