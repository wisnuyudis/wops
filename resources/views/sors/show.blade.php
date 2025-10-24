@extends('layouts.app')

@section('page-title', 'SOR Detail')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>SOR Details</h6>
                    <div>
                        <a href="{{ route('sors.edit', $sor) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('sors.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">SOR Number</label>
                            <p class="text-sm">{{ $sor->sor }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Init Date</label>
                            <p class="text-sm">{{ $sor->init_date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Customer</label>
                            <p class="text-sm">{{ $sor->customer->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Product</label>
                            <p class="text-sm">{{ $sor->product->name }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Status</label>
                            <p>
                                <span class="badge bg-gradient-{{ $sor->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($sor->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Assigned Users</label>
                            <p class="text-sm">
                                @if($sor->users->count() > 0)
                                    @foreach($sor->users as $user)
                                        <span class="badge bg-gradient-info me-1">{{ $user->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No users assigned</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Description</label>
                            <p class="text-sm" style="white-space: pre-line;">{{ $sor->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Daily Activities -->
        <div class="card">
            <div class="card-header pb-0">
                <h6>Related Daily Activities</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Product</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sor->dailyActivities as $activity)
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
                                    <p class="text-xs font-weight-bold mb-0">{{ $activity->cust_name ?? ($activity->sor ? $activity->sor->customer->name : '-') }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $activity->product ?? '-' }}</p>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-{{ $activity->status == 'completed' ? 'success' : ($activity->status == 'in_progress' ? 'info' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-sm">No daily activities found for this SOR</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
