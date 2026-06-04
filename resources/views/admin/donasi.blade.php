@extends('layouts.admin')
@section('title', 'Kelola Donasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">Kelola Donasi</h2>
        <p class="text-secondary mb-0">Verifikasi manual bukti transfer pembayaran dari para donatur.</p>
    </div>
    <a href="{{ route('donasi') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Ke Halaman Utama Donasi
    </a>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header bg-white border-bottom-0 pb-0">
        {{-- Search Form --}}
        <div class="mb-3">
            <form action="{{ route('admin.donasi') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama donatur, kode donasi, atau no. telepon..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                    </button>
                </div>
                @if(request('search'))
                    <div class="col-12">
                        <a href="{{ route('admin.donasi') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <ul class="nav nav-tabs border-bottom-0" id="donasiTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.donasi', array_merge(request()->query(), ['status' => null])) }}" role="tab">Semua <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold text-warning {{ request('status') === 'pending' ? 'active' : '' }}" href="{{ route('admin.donasi', array_merge(request()->query(), ['status' => 'pending'])) }}" role="tab">Menunggu <span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold text-success {{ request('status') === 'approved' ? 'active' : '' }}" href="{{ route('admin.donasi', array_merge(request()->query(), ['status' => 'approved'])) }}" role="tab">Terverifikasi <span class="badge bg-success ms-1">{{ $counts['approved'] }}</span></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold text-danger {{ request('status') === 'rejected' ? 'active' : '' }}" href="{{ route('admin.donasi', array_merge(request()->query(), ['status' => 'rejected'])) }}" role="tab">Ditolak <span class="badge bg-danger ms-1">{{ $counts['rejected'] }}</span></a>
            </li>
        </ul>
    </div>
    <div class="card-body border-top p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode Donasi</th>
                        <th>Donatur & Kontak</th>
                        <th class="text-end">Jumlah</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Bukti Transfer</th>
                        <th class="pe-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border px-2 py-1 user-select-all font-monospace" style="font-size: 0.85rem;">{{ $donation->kode_donasi }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $donation->nama_lengkap }}</div>
                                    <div class="small text-muted mt-1">
                                        @php
                                            $waNumber = preg_replace('/^0/', '62', $donation->nomor_telepon);
                                        @endphp
                                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="text-decoration-none text-muted hover-primary">
                                            <i class="fa-brands fa-whatsapp text-success"></i> {{ $donation->nomor_telepon }}
                                        </a>
                                    </div>
                                    @if($donation->campaign)
                                    <div class="mt-1">
                                        <span class="badge badge-soft-primary" style="font-size: 0.7rem;">
                                            <i class="fa-solid fa-bullseye me-1"></i> {{ Str::limit($donation->campaign->title, 30) }}
                                        </span>
                                    </div>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    Rp {{ number_format($donation->jumlah_donasi, 0, ',', '.') }}
                                </td>
                                <td>
                                    {{ $donation->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="text-center">
                                    @if($donation->status === 'pending')
                                        <span class="badge badge-soft-warning">Menunggu</span>
                                    @elseif($donation->status === 'approved')
                                        <span class="badge badge-soft-success">Terverifikasi</span>
                                    @else
                                        <span class="badge badge-soft-danger">Gagal</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#proofModal{{ $donation->id }}" style="font-size: 0.75rem">
                                        <i class="fa-regular fa-image me-1"></i> Lihat Bukti
                                    </button>
                                </td>
                                <td class="pe-4 text-center">
                                    @if($donation->status === 'pending')
                                        <div class="d-flex justify-content-center gap-1">
                                            <form action="{{ route('admin.donasi.approve', $donation->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                            </form>
                                            <form action="{{ route('admin.donasi.reject', $donation->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="small text-muted">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fa-solid fa-file-invoice-dollar fa-2x text-muted mb-3"></i>
                                    <p class="text-muted fw-bold mb-0">Belum ada donasi untuk pencarian ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @foreach($donations as $donation)
                <div class="modal fade" id="proofModal{{ $donation->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pratinjau Bukti Transfer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center bg-light">
                                <img src="{{ asset('storage/' . $donation->bukti_transfer) }}" alt="Bukti Pembayaran" class="img-fluid rounded border shadow-sm" style="max-height: 500px; object-fit: contain;">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Pagination --}}
                @if($donations->hasPages())
                <div class="px-4 py-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Halaman {{ $donations->currentPage() }} dari {{ $donations->lastPage() }} • Menampilkan {{ $donations->count() }} dari {{ $donations->total() }} donasi
                        </small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                @if($donations->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">‹ Sebelumnya</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $donations->previousPageUrl() }}">‹ Sebelumnya</a></li>
                                @endif

                                @foreach($donations->getUrlRange(1, $donations->lastPage()) as $page => $url)
                                    @if($page == $donations->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                @if($donations->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $donations->nextPageUrl() }}">Selanjutnya ›</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Selanjutnya ›</span></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
                @endif
        </div>
    </div>
</div>
@endsection
