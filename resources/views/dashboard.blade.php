@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, Admin! Here's what's happening today.</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i> New Report
            </button>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon-wrap bg-primary-subtle text-primary me-3">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Sales</div>
                        <div class="stat-value">$12,345</div>
                        <div class="stat-change positive mt-1">
                            <i class="fa-solid fa-arrow-up me-1"></i> 12.5%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon-wrap bg-success-subtle text-success me-3">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-label">New Users</div>
                        <div class="stat-value">845</div>
                        <div class="stat-change positive mt-1">
                            <i class="fa-solid fa-arrow-up me-1"></i> 8.2%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon-wrap bg-warning-subtle text-warning me-3">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-value">3,542</div>
                        <div class="stat-change negative mt-1">
                            <i class="fa-solid fa-arrow-down me-1"></i> 2.4%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon-wrap bg-info-subtle text-info me-3">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <div class="stat-label">Revenue</div>
                        <div class="stat-value">$42,850</div>
                        <div class="stat-change positive mt-1">
                            <i class="fa-solid fa-arrow-up me-1"></i> 18.2%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Recent Orders Table -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <button class="btn btn-sm btn-light"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="text-primary fw-semibold">#ORD-001</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary-subtle text-primary me-2">JD</div>
                                        John Doe
                                    </div>
                                </td>
                                <td>Today, 10:24 AM</td>
                                <td><span class="badge badge-soft-success">Completed</span></td>
                                <td class="fw-semibold">$125.00</td>
                            </tr>
                            <tr>
                                <td><span class="text-primary fw-semibold">#ORD-002</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=28c76f&color=fff" class="avatar avatar-sm me-2" alt="Avatar">
                                        Jane Smith
                                    </div>
                                </td>
                                <td>Today, 09:12 AM</td>
                                <td><span class="badge badge-soft-warning">Pending</span></td>
                                <td class="fw-semibold">$89.50</td>
                            </tr>
                            <tr>
                                <td><span class="text-primary fw-semibold">#ORD-003</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-danger-subtle text-danger me-2">MJ</div>
                                        Mike Johnson
                                    </div>
                                </td>
                                <td>Yesterday</td>
                                <td><span class="badge badge-soft-danger">Cancelled</span></td>
                                <td class="fw-semibold">$249.99</td>
                            </tr>
                            <tr>
                                <td><span class="text-primary fw-semibold">#ORD-004</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Sarah+Lee&background=00cfe8&color=fff" class="avatar avatar-sm me-2" alt="Avatar">
                                        Sarah Lee
                                    </div>
                                </td>
                                <td>Yesterday</td>
                                <td><span class="badge badge-soft-success">Completed</span></td>
                                <td class="fw-semibold">$45.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Activity/Target Card -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Target</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4 mt-2">
                        <div class="display-5 fw-bold text-primary mb-1">78%</div>
                        <div class="text-secondary">of $50,000 target</div>
                    </div>
                    
                    <div class="progress mb-4">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between mb-3">
                            <span class="text-secondary"><i class="fa-solid fa-circle text-primary me-2" style="font-size: 8px;"></i> Completed</span>
                            <span class="fw-semibold">$39,000</span>
                        </li>
                        <li class="d-flex justify-content-between mb-3">
                            <span class="text-secondary"><i class="fa-solid fa-circle text-light me-2" style="font-size: 8px;"></i> Remaining</span>
                            <span class="fw-semibold">$11,000</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
