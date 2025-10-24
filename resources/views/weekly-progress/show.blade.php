@extends('layouts.app')

@section('page-title', 'Weekly Progress Detail')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Weekly Progress Details</h6>
                    <div>
                        @if(auth()->user()->role === 'admin' || $weeklyProgress->user_id === auth()->id())
                        <a href="{{ route('weekly-progress.edit', $weeklyProgress) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endif
                        <a href="{{ route('weekly-progress.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">User</label>
                            <p class="text-sm">{{ $weeklyProgress->user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Year</label>
                            <p class="text-sm">{{ $weeklyProgress->year }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Week Number</label>
                            <p class="text-sm">Week {{ $weeklyProgress->week_number }}</p>
                        </div>
                    </div>
                </div>
                
                <hr class="horizontal dark">
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label text-sm font-weight-bold">Last Week Status</label>
                            <div class="card bg-gray-100">
                                <div class="card-body">
                                    <p class="text-sm mb-0">{{ $weeklyProgress->last_week_status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label text-sm font-weight-bold text-primary">P1 - Must Complete This Week</label>
                            <div class="card bg-gradient-primary">
                                <div class="card-body">
                                    <p class="text-sm text-white mb-0">{{ $weeklyProgress->p1 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label text-sm font-weight-bold text-info">P2 - Secondary Priority</label>
                            <div class="card bg-gradient-info">
                                <div class="card-body">
                                    <p class="text-sm text-white mb-0">{{ $weeklyProgress->p2 ?? 'No secondary priority set' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold text-secondary">P3 - 2+ Weeks Ahead</label>
                            <div class="card bg-gradient-secondary">
                                <div class="card-body">
                                    <p class="text-sm text-white mb-0">{{ $weeklyProgress->p3 ?? 'No long-term plans set' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
