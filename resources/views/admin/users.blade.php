@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Manajemen Pengguna</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="fa-solid fa-plus me-2"></i> Tambah Pengguna Baru
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        {{-- Search Form --}}
        <form action="{{ route('admin.users') }}" method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, login ID, atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                    </button>
                </div>
            </div>
            @if(request('search'))
                <div class="mt-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Login ID / No. Telp</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bergabung Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold d-flex align-items-center gap-2">
                                {{ $user->name }}
                                @if($user->id === Auth::id())
                                    <span class="badge bg-info text-white" style="font-size: 0.65rem;">ANDA</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->login }}</td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : 'success' }}">
                                {{ $user->role == 'admin' ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini secara permanen?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Edit User Modal -->
                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ url('/admin/users/'.$user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Profil Pengguna</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Login ID / No. Telp</label>
                                            <input type="text" class="form-control" name="login" value="{{ $user->login }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email (Opsional)</label>
                                            <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ubah Kata Sandi (Opsional)</label>
                                            <input type="password" class="form-control" name="password" minlength="6" placeholder="Biarkan kosong jika tidak diubah">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select" required>
                                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Donatur Biasa (User)</option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Pihak Panti (Admin)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{-- Pagination Info --}}
            @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }} • Menampilkan {{ $users->count() }} dari {{ $users->total() }} pengguna
                </small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if($users->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">‹ Sebelumnya</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $users->previousPageUrl() }}">‹ Sebelumnya</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if($page == $users->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($users->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $users->nextPageUrl() }}">Selanjutnya ›</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">Selanjutnya ›</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Login ID / No. Telp</label>
                        <input type="text" class="form-control" name="login" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (Opsional)</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="user">Donatur Biasa (User)</option>
                            <option value="admin">Pihak Panti (Admin)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
