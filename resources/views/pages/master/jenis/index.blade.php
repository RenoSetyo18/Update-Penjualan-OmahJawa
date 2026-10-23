@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Master Jenis Menu</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('jenis.create') }}" class="btn btn-primary btn-create-action">
                <span class="btn-create-icon" aria-hidden="true"><i class="fas fa-plus"></i></span>
                Tambah Jenis
            </a>
        </div>
    </div>
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Jenis Menu</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <label class="mr-2">Tampilkan per halaman:</label>
                <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach([5, 10, 25, 50] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <noscript>
                    <button type="submit" class="btn btn-sm btn-primary ml-2">Terapkan</button>
                </noscript>
            </form>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jenis</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jenis_list as $jenis)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jenis->nama_jenis }}</td>
                            <td>{{ $jenis->deskripsi ?? '-' }}</td>
                            <td>
                                <a href="{{ route('jenis.edit', $jenis->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('jenis.destroy', $jenis->id) }}" method="POST" style="display:inline;" class="delete-confirm" data-item-name="jenis '{{ $jenis->nama_jenis }}'">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data jenis menu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $jenis_list->firstItem() ?? 0 }} sampai {{ $jenis_list->lastItem() ?? 0 }} dari {{ $jenis_list->total() }} data
                </div>
                <div>
                    {{ $jenis_list->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
