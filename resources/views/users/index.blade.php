@extends('layouts.app')

@section('page-title', 'User Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Users List</h6>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New User
                    </a>
                    @endif
                </div>
                <!-- Search Box -->
                <div class="px-0 pb-2">
                    <form action="{{ route('users.index') }}" method="GET" id="searchForm">
                        <div class="input-group input-group-sm" style="max-width: 300px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control form-control-sm" name="search" 
                                   placeholder="Search users..." value="{{ request('search') }}" id="searchInput">
                        </div>
                    </form>
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $user->username }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->job ? $user->job->name : '-' }}</p>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-{{ $user->role == 'admin' ? 'primary' : 'secondary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('users.edit', $user) }}" class="text-secondary font-weight-bold text-xs me-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                                <td colspan="5" class="text-center">No users found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $users->links('vendor.pagination.simple') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Realtime search with debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500);
});
</script>
@endpush
