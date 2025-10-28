@extends('layouts.app')

@section('page-title', 'Daily Activities')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Daily Activities List</h6>
                    <a href="{{ route('daily-activities.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Activity
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('daily-activities.index') }}" id="searchForm">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search activities..." value="{{ request('search') }}">
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">SOR</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-xs">{{ $activity->user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
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
                                    <p class="text-xs font-weight-bold mb-0">{{ Str::limit($activity->action, 40) }}</p>
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
                                <td colspan="7" class="text-center">No daily activities found</td>
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
