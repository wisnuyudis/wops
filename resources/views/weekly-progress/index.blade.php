@extends('layouts.app')

@section('page-title', 'Weekly Progress')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Weekly Progress List</h6>
                    <div>
                        @if(auth()->user()->role === 'admin')
                        <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </button>
                        @endif
                        <a href="{{ route('weekly-progress.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('weekly-progress.index') }}" id="searchForm">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search progress..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                @if(session('success'))
                <div class="alert alert-success mx-4 mt-3">
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('info'))
                <div class="alert alert-info mx-4 mt-3">
                    {{ session('info') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger mx-4 mt-3">
                    {{ session('error') }}
                </div>
                @endif
                
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Year</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Week Period</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Week Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($weeklyProgresses as $progress)
                            @php
                                $date = new DateTime();
                                $date->setISODate($progress->year, $progress->week_number);
                                // Set to Monday
                                $date->modify('Monday this week');
                                $weekStart = $date->format('d M');
                                // Add 4 days to get Friday
                                $date->modify('+4 days');
                                $weekEnd = $date->format('d M Y');
                            @endphp
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 ms-3">{{ $progress->year }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">Week {{ $progress->week_number }}<br><small class="text-muted">{{ $weekStart }} - {{ $weekEnd }}</small></p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $progress->user->name }}</p>
                                </td>
                                <td>
                                    <p class="text-xs mb-0">{{ Str::limit($progress->last_week_status, 50) }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('weekly-progress.show', $progress) }}" class="btn btn-link text-secondary mb-0" title="View">
                                        <i class="fa fa-eye text-xs"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin' || $progress->user_id === auth()->id())
                                    <a href="{{ route('weekly-progress.edit', $progress) }}" class="btn btn-link text-secondary mb-0" title="Edit">
                                        <i class="fa fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('weekly-progress.destroy', $progress) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-secondary mb-0" onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="fa fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <p class="text-xs text-secondary mb-0">No weekly progress found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 mt-3">
                    {{ $weeklyProgresses->links('vendor.pagination.simple') }}
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
                <h5 class="modal-title" id="exportModalLabel">Export Weekly Progress to Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('weekly-progress.export') }}" id="exportForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="week_from" class="form-control-label">Week From <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="week_from" name="week_from" 
                                       min="1" max="53" placeholder="e.g., 43" required>
                                <small class="form-text text-muted">Week number (1-53)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="week_to" class="form-control-label">Week To <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="week_to" name="week_to" 
                                       min="1" max="53" placeholder="e.g., 44" required>
                                <small class="form-text text-muted">Week number (1-53)</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="year" class="form-control-label">Year <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="year" name="year" 
                               min="2020" max="2030" value="{{ date('Y') }}" required>
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
</script>
@endpush
