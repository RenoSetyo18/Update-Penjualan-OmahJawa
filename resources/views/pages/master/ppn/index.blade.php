@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Pengaturan PPN</h1>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pengaturan PPN</h3>
        </div>
        <form action="{{ route('master-ppn.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="is_enabled" value="0">
                        <input
                            type="checkbox"
                            class="custom-control-input @error('is_enabled') is-invalid @enderror"
                            id="is_enabled"
                            name="is_enabled"
                            value="1"
                            @checked(old('is_enabled', $setting->is_enabled))
                        >
                        <label class="custom-control-label" for="is_enabled">PPN aktif</label>
                    </div>
                    <small class="form-text text-muted">Jika tidak dicentang, transaksi baru tidak akan dikenakan PPN meskipun tarif di bawah ini diisi.</small>
                    @error('is_enabled')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="rate">Persentase PPN</label>
                    <div class="input-group" style="max-width: 280px;">
                        <input
                            type="number"
                            class="form-control @error('rate') is-invalid @enderror"
                            id="rate"
                            name="rate"
                            min="0"
                            max="100"
                            step="0.01"
                            value="{{ old('rate', $setting->rate) }}"
                            required
                        >
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Tarif ini digunakan untuk pesanan baru. Transaksi yang sudah dibuat tetap memakai tarif saat transaksi disimpan.</small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan PPN</button>
            </div>
        </form>
    </div>
@endsection
