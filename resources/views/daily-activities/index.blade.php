@extends('layouts.app')

@section('page-title', 'Daily Activities')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Daily Activities List</h6>
                    <div>
                        @if(auth()->user()->role === 'admin')
                        <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </button>
                        @endif
                        <a href="{{ route('daily-activities.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Activity
                        </a>
                    </div>
                </div>
                <div class="row mb-3">
                    @if(auth()->user()->role === 'admin')
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('daily-activities.index') }}" id="filterForm">
                            <label class="form-label text-xs mb-1">Filter by User</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <select name="user_id" class="form-control" id="userFilter" onchange="this.form.submit()">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                            </div>
                        </form>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('daily-activities.index') }}" id="monthForm">
                            <label class="form-label text-xs mb-1">Period</label>
                            <input type="month" name="month" class="form-control form-control-sm" 
                                   value="{{ $selectedMonth }}" onchange="this.form.submit()">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(auth()->user()->role === 'admin' && request('user_id'))
                                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                            @endif
                        </form>
                    </div>
                    <div class="col-md-3 ms-auto">
                        <form method="GET" action="{{ route('daily-activities.index') }}" id="searchForm">
                            <label class="form-label text-xs mb-1">Search</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search activities..." value="{{ request('search') }}">
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                @if(auth()->user()->role === 'admin' && request('user_id'))
                                    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                @if(auth()->user()->role === 'admin')
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                @endif
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">SOR</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Product</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Items</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                            <tr>
                                @if(auth()->user()->role === 'admin')
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-xs">{{ $activity->user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                @endif
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $activity->date->format('d M Y') }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $activity->sor ? $activity->sor->sor : '-' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $activity->cust_name ?? ($activity->sor ? $activity->sor->customer->name : '-') }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $activity->product ?? ($activity->sor ? $activity->sor->product->name : '-') }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ Str::limit($activity->action, 30) }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $activity->jobItem ? $activity->jobItem->name : '-' }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-{{ $activity->status == 'completed' ? 'success' : ($activity->status == 'in_progress' ? 'info' : ($activity->status == 'on_hold' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('daily-activities.show', $activity) }}" class="text-info font-weight-bold text-xs me-2">
                                        View
                                    </a>
                                    @if(auth()->user()->role === 'admin' || $activity->user_id === auth()->id())
                                    <a href="{{ route('daily-activities.edit', $activity) }}" class="text-secondary font-weight-bold text-xs me-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('daily-activities.destroy', $activity) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this activity?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger font-weight-bold text-xs border-0 bg-transparent" style="cursor: pointer;">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? '9' : '8' }}" class="text-center">No daily activities found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 mt-3">
                    {{ $activities->links('vendor.pagination.simple') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export Daily Activities to Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('daily-activities.export') }}" id="exportForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="export_user_id" class="form-control-label">Select User <span class="text-danger">*</span></label>
                        <select class="form-control" id="export_user_id" name="user_id" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="export_sor_id" class="form-control-label">Select SOR (Optional)</label>
                        <select class="form-control" id="export_sor_id" name="sor_id">
                            <option value="">All SORs</option>
                            @foreach($sors as $sor)
                                <option value="{{ $sor->id }}">{{ $sor->sor }} - {{ $sor->customer->cust_name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date_from" class="form-control-label">Date From <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_from" name="date_from" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date_to" class="form-control-label">Date To <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_to" name="date_to" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            document.getElementById('searchForm').submit();
        }, 500);
    });
    
    // Set default date range when export modal is opened
    document.getElementById('exportModal').addEventListener('show.bs.modal', function (event) {
        const dateFromInput = document.getElementById('date_from');
        const dateToInput = document.getElementById('date_to');
        
        // Set default to first and last day of current month if not already set
        if (!dateFromInput.value) {
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            
            dateFromInput.value = firstDay.toISOString().split('T')[0];
            dateToInput.value = lastDay.toISOString().split('T')[0];
        }
    });
</script>
@endpush
