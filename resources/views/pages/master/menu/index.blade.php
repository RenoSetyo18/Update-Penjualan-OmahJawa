@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Master Menu</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('master-menu.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Menu
            </a>
        </div>
    </div>
@endsection

@section('content')
    @php
        $jenisSortOrder = request('sort') === 'jenis' && request('order') === 'asc' ? 'desc' : 'asc';
        $jenisSortUrl = route('master-menu.index', array_merge(request()->except(['sort', 'order', 'page']), [
            'sort' => 'jenis',
            'order' => $jenisSortOrder,
            'per_page' => request('per_page', 10)
        ]));
        $jenisSortIndicator = request('sort') === 'jenis'
            ? (request('order') === 'asc' ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>')
            : ' <i class="fas fa-sort"></i>';
    @endphp

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
            <h3 class="card-title">Daftar Menu</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <label class="mr-2">Tampilkan per halaman:</label>
                <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach([5, 10, 25, 50] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 5) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <noscript>
                    <button type="submit" class="btn btn-sm btn-primary ml-2">Terapkan</button>
                </noscript>
            </form>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Menu</th>
                        <th>
                            <a href="{{ $jenisSortUrl }}" style="display: block; width: 100%; height: 100%; color: inherit; text-decoration: none; cursor: pointer;">
                                Jenis{!! $jenisSortIndicator !!}
                            </a>
                        </th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $menu->nama }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $menu->jenis->nama_jenis ?? 'N/A' }}</span>
                            </td>
                            <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td>{{ Str::limit($menu->deskripsi ?? '-', 50) }}</td>
                            <td>
                                @if($menu->active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('master-menu.edit', $menu->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('master-menu.destroy', $menu->id) }}" method="POST" style="display:inline;" class="delete-confirm" data-item-name="menu '{{ $menu->nama }}'">
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
                            <td colspan="7" class="text-center">Tidak ada data menu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="row mt-3 align-items-center">
                <div class="col-sm-6">
                    Menampilkan {{ $menus->firstItem() ?? 0 }} sampai {{ $menus->lastItem() ?? 0 }} dari {{ $menus->total() }} data
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    @php
                        $currentPage = $menus->currentPage();
                        $lastPage = $menus->lastPage();
                        $pageLinks = [];

                        if ($lastPage <= 6) {
                            $pageLinks = range(1, $lastPage);
                        } else {
                            $pageLinks = [1, 2, 3, 4, 5];
                            if ($currentPage > 5) {
                                $pageLinks[] = 'ellipsis';
                            }
                            $pageLinks[] = $lastPage;
                        }

                        $buildPageUrl = function ($page) {
                            $query = request()->except('page');
                            if ($page > 1) {
                                $query['page'] = $page;
                            } else {
                                unset($query['page']);
                            }

                            return route('master-menu.index', $query);
                        };
                    @endphp
                    @if ($menus->hasPages())
                        <nav aria-label="Pagination">
                            <ul class="pagination mb-0">
                                <li class="page-item {{ $menus->onFirstPage() ? 'disabled' : '' }}">
                                    @if ($menus->onFirstPage())
                                        <span class="page-link" aria-hidden="true">&laquo;</span>
                                    @else
                                        <a class="page-link" href="{{ $buildPageUrl($currentPage - 1) }}" aria-label="Sebelumnya">&laquo;</a>
                                    @endif
                                </li>

                                @foreach ($pageLinks as $page)
                                    @if ($page === 'ellipsis')
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @else
                                        <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                                            @if ($page == $currentPage)
                                                <span class="page-link">{{ $page }}</span>
                                            @else
                                                <a class="page-link" href="{{ $buildPageUrl($page) }}">{{ $page }}</a>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach

                                <li class="page-item {{ $menus->hasMorePages() ? '' : 'disabled' }}">
                                    @if ($menus->hasMorePages())
                                        <a class="page-link" href="{{ $buildPageUrl($currentPage + 1) }}" aria-label="Berikutnya">&raquo;</a>
                                    @else
                                        <span class="page-link" aria-hidden="true">&raquo;</span>
                                    @endif
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
