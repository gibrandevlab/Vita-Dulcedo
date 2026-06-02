@extends('layouts.admin')
@section('title', 'Kelola Kegiatan & Kunjungan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">Kelola Kegiatan & Kunjungan</h2>
        <p class="text-secondary mb-0">Verifikasi permohonan kunjungan dan jadwalkan kegiatan panti.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addActivityModal">
            <i class="fa-solid fa-plus me-2"></i> Tambah Kegiatan
        </button>
        <a href="{{ route('kegiatan') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-calendar-check me-2"></i> Lihat Kalender
        </a>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Gagal menyimpan kegiatan:</strong>
    <ul class="mb-0 mt-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header bg-white border-bottom-0 pb-0">
        {{-- Search Form --}}
        <div class="mb-3">
            <form action="{{ route('admin.kegiatan') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama pengunjung atau kode kunjungan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                    </button>
                </div>
                @if(request('search'))
                    <div class="col-12">
                        <a href="{{ route('admin.kegiatan') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <ul class="nav nav-tabs border-bottom-0" id="kegiatanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.kegiatan', array_merge(request()->query(), ['status' => null])) }}" role="tab">Semua <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold text-warning {{ request('status') === 'pending' ? 'active' : '' }}" href="{{ route('admin.kegiatan', array_merge(request()->query(), ['status' => 'pending'])) }}" role="tab">Menunggu <span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] }}</span></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold text-success {{ request('status') === 'approved' ? 'active' : '' }}" href="{{ route('admin.kegiatan', array_merge(request()->query(), ['status' => 'approved'])) }}" role="tab">Disetujui <span class="badge bg-success ms-1">{{ $counts['approved'] }}</span></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-semibold text-danger {{ request('status') === 'rejected' ? 'active' : '' }}" href="{{ route('admin.kegiatan', array_merge(request()->query(), ['status' => 'rejected'])) }}" role="tab">Ditolak <span class="badge bg-danger ms-1">{{ $counts['rejected'] }}</span></a>
            </li>
        </ul>
    </div>
    <div class="card-body border-top p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Pengunjung & Kontak</th>
                        <th>Jml Orang</th>
                        <th>Waktu Kunjungan</th>
                        <th>Status</th>
                        <th class="pe-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $visit)
                            <tr>
                                <td class="ps-4">
                                    @if(str_starts_with($visit->kode_kunjungan, 'INT'))
                                        <span class="badge badge-soft-primary px-2 py-1 user-select-all">{{ $visit->kode_kunjungan }}</span>
                                    @else
                                        <span class="badge bg-light text-dark border px-2 py-1 user-select-all">{{ $visit->kode_kunjungan }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $visit->nama_pengunjung }}</div>
                                    <div class="small text-muted mt-1">
                                        @php
                                            $loginValue = $visit->user->login ?? '';
                                            $isPhone = preg_match('/^[0-9+]+$/', $loginValue);
                                            $waNumber = $isPhone ? preg_replace('/^0/', '62', $loginValue) : '';
                                        @endphp
                                        @if($isPhone)
                                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="text-decoration-none"><i class="fa-brands fa-whatsapp text-success"></i> {{ $loginValue }}</a>
                                        @else
                                            <span><i class="fa-regular fa-envelope"></i> {{ $loginValue }}</span>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light border mt-2" style="font-size: 0.75rem" data-bs-toggle="modal" data-bs-target="#tujuanModal{{ $visit->id }}">
                                        <i class="fa-regular fa-eye me-1"></i> Lihat Tujuan
                                    </button>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6">{{ $visit->jumlah_pengunjung }}</span> <span class="small text-muted">orang</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle p-2 rounded text-primary">
                                            <i class="fa-regular fa-calendar-check fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">
                                                @if($visit->is_routine)
                                                    Mulai: {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->format('d M Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->format('d M Y') }}
                                                @endif
                                            </div>
                                            @if($visit->is_routine)
                                                <div class="small text-primary fw-semibold" style="font-size: 0.75rem;">
                                                    Rutin
                                                    @php
                                                        $dayNames = [1=>'Sen', 2=>'Sel', 3=>'Rab', 4=>'Kam', 5=>'Jum', 6=>'Sab', 7=>'Min'];
                                                        $dArray = $visit->routine_days ? explode(',', $visit->routine_days) : [];
                                                        $dLabels = array_map(fn($d) => $dayNames[$d] ?? '', $dArray);
                                                    @endphp
                                                    ({{ implode(', ', $dLabels) }})
                                                </div>
                                                <div class="text-muted" style="font-size: 0.7rem;">s/d {{ \Carbon\Carbon::parse($visit->routine_end_date)->format('d M Y') }}</div>
                                            @endif
                                            <div class="text-muted small mt-1">
                                                <i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($visit->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($visit->jam_selesai)->format('H:i') }} WIB
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($visit->status === 'pending')
                                        <span class="badge badge-soft-warning">Menunggu</span>
                                    @elseif($visit->status === 'approved')
                                        <span class="badge badge-soft-success">Disetujui</span>
                                    @else
                                        <span class="badge badge-soft-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-center">
                                    @if($visit->status === 'pending')
                                        <div class="d-flex justify-content-center gap-1">
                                            <form action="{{ route('admin.kegiatan.approve', $visit->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                            </form>
                                            <form action="{{ route('admin.kegiatan.reject', $visit->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="small text-muted">Selesai</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Tujuan Modal -->
                            <div class="modal fade" id="tujuanModal{{ $visit->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tujuan Kunjungan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="small text-muted mb-1">Oleh: <strong class="text-dark">{{ $visit->nama_pengunjung }}</strong></p>
                                            <div class="p-3 bg-light rounded border text-wrap" style="white-space: pre-wrap;">{{ $visit->tujuan_kunjungan }}</div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fa-regular fa-calendar-xmark fa-2x text-muted mb-3"></i>
                            <p class="text-muted fw-bold mb-0">Belum ada data untuk pencarian ini.</p>
                        </td>
                    </tr>
                    @endempty
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($visits->hasPages())
        <div class="px-4 py-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Halaman {{ $visits->currentPage() }} dari {{ $visits->lastPage() }} • Menampilkan {{ $visits->count() }} dari {{ $visits->total() }} data
                </small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        @if($visits->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">‹ Sebelumnya</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $visits->previousPageUrl() }}">‹ Sebelumnya</a></li>
                        @endif

                        @foreach($visits->getUrlRange(1, $visits->lastPage()) as $page => $url)
                            @if($page == $visits->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        @if($visits->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $visits->nextPageUrl() }}">Selanjutnya ›</a></li>
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

<!-- Add Activity Modal -->
<div class="modal fade" id="addActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <form action="{{ route('admin.kegiatan.storeInternal') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kegiatan Internal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama / Penyelenggara Kegiatan</label>
                        <input type="text" class="form-control" name="nama_kegiatan" required placeholder="Contoh: Pengurus Panti / Pengajian Rutin">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi / Detail Kegiatan</label>
                        <textarea class="form-control" name="tujuan_kegiatan" rows="3" required placeholder="Detail tentang kegiatan rutin atau acara internal..."></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal_kunjungan" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Estimasi Peserta</label>
                            <input type="number" class="form-control" name="jumlah_pengunjung" required min="1" value="10">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" required value="08:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" required value="10:00">
                        </div>
                    </div>

                    <hr>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="isRoutineToggle" name="is_routine" value="1">
                        <label class="form-check-label fw-bold" for="isRoutineToggle">Jadikan Kegiatan Rutin (Berulang)</label>
                    </div>

                    <div id="routineFields" class="d-none bg-primary-subtle p-3 rounded border border-primary border-opacity-25">
                        <label class="form-label fw-bold text-primary mb-2">Pilih Hari Rutinitas</label>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @php
                                $days = [
                                    1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                                    4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
                                ];
                            @endphp
                            @foreach($days as $num => $day)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="routine_days[]" value="{{ $num }}" id="day{{ $num }}">
                                <label class="form-check-label small" for="day{{ $num }}">{{ $day }}</label>
                            </div>
                            @endforeach
                        </div>
                        <label class="form-label fw-bold text-primary">Berulang Hingga Tanggal</label>
                        <input type="date" class="form-control" name="routine_end_date" id="routineEndDateInput" min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kegiatan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isRoutineToggle = document.getElementById('isRoutineToggle');
    const routineFields = document.getElementById('routineFields');
    const routineEndDateInput = document.getElementById('routineEndDateInput');

    isRoutineToggle.addEventListener('change', function() {
        if (this.checked) {
            routineFields.classList.remove('d-none');
            routineEndDateInput.required = true;
        } else {
            routineFields.classList.add('d-none');
            routineEndDateInput.required = false;
        }
    });
});
</script>
@endsection
