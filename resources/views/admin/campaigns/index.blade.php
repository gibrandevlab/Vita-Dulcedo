@extends('layouts.admin')
@section('title', 'Manajemen Kebutuhan Panti')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Manajemen Kebutuhan Panti (Campaigns)</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCampaignModal">
        <i class="fa-solid fa-plus me-2"></i> Tambah Kebutuhan
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        {{-- Search Form --}}
        <form action="{{ route('admin.campaigns.index') }}" method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul atau deskripsi kebutuhan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                    </button>
                </div>
            </div>
            @if(request('search'))
                <div class="mt-2">
                    <a href="{{ route('admin.campaigns.index') }}" class="btn btn-sm btn-outline-secondary">
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
                        <th>Judul Kebutuhan</th>
                        <th>Progress Dana</th>
                        <th>Status</th>
                        <th>Tenggat Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold">{{ $campaign->title }}</div>
                        </td>
                        <td>
                            @php
                                $percentage = $campaign->target_amount > 0 ? min(100, round(($campaign->collected_amount / $campaign->target_amount) * 100)) : 0;
                            @endphp
                            <div class="small mb-1">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }} / Rp {{ number_format($campaign->target_amount, 0, ',', '.') }} ({{ $percentage }}%)</div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $campaign->status == 'active' ? 'primary' : 'success' }}">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td>{{ $campaign->deadline ? \Carbon\Carbon::parse($campaign->deadline)->format('d M Y') : 'Tanpa Batas' }}</td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editCampaignModal{{ $campaign->id }}">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editCampaignModal{{ $campaign->id }}" tabindex="-1" aria-labelledby="editCampaignModalLabel{{ $campaign->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editCampaignModalLabel{{ $campaign->id }}">Edit Kebutuhan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Judul Kebutuhan</label>
                                            <input type="text" class="form-control" name="title" value="{{ $campaign->title }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Target Dana (Rp)</label>
                                            <input type="number" class="form-control" name="target_amount" value="{{ $campaign->target_amount }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tenggat Waktu</label>
                                            <input type="date" class="form-control" name="deadline" value="{{ $campaign->deadline }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="active" {{ $campaign->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="completed" {{ $campaign->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Gambar Ilustrasi</label>
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                            @if($campaign->image)
                                                <div class="mt-2 text-muted small">Sudah ada gambar (upload baru untuk mengganti)</div>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea class="form-control" name="description" rows="3">{{ $campaign->description }}</textarea>
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

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada program kebutuhan panti.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $campaigns->links() }}
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCampaignModal" tabindex="-1" aria-labelledby="addCampaignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCampaignModalLabel">Tambah Kebutuhan Panti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Kebutuhan</label>
                        <input type="text" class="form-control" name="title" required placeholder="Contoh: Beli Sembako Ramadhan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target Dana (Rp)</label>
                        <input type="number" class="form-control" name="target_amount" required placeholder="Contoh: 5000000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tenggat Waktu</label>
                        <input type="date" class="form-control" name="deadline">
                        <div class="form-text">Kosongkan jika program berlangsung selamanya hingga target tercapai.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Ilustrasi</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Tambahan</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Tuliskan alasan atau rincian kebutuhan..."></textarea>
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
