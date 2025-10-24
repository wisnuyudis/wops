@extends('layouts.app')

@section('page-title', 'Daily Activity Detail')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Daily Activity Details</h6>
                    <div>
                        @if(auth()->user()->role === 'admin' || $dailyActivity->user_id === auth()->id())
                        <a href="{{ route('daily-activities.edit', $dailyActivity) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endif
                        <a href="{{ route('daily-activities.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">User</label>
                            <p class="text-sm">{{ $dailyActivity->user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Date</label>
                            <p class="text-sm">{{ $dailyActivity->date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">SOR</label>
                            <p class="text-sm">{{ $dailyActivity->sor ? $dailyActivity->sor->sor : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Customer</label>
                            <p class="text-sm">{{ $dailyActivity->cust_name ?? ($dailyActivity->sor ? $dailyActivity->sor->customer->name : '-') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Product</label>
                            <p class="text-sm">{{ $dailyActivity->product ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">PIC</label>
                            <p class="text-sm">{{ $dailyActivity->pic ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Job Type</label>
                            <p class="text-sm">{{ $dailyActivity->jobType ? $dailyActivity->jobType->name : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Job Item</label>
                            <p class="text-sm">{{ $dailyActivity->jobItem ? $dailyActivity->jobItem->name : '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Status</label>
                            <p>
                                <span class="badge bg-gradient-{{ $dailyActivity->status == 'completed' ? 'success' : ($dailyActivity->status == 'in_progress' ? 'info' : ($dailyActivity->status == 'on_hold' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $dailyActivity->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Action</label>
                            <p class="text-sm">{{ $dailyActivity->action ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Objective</label>
                            <p class="text-sm">{{ $dailyActivity->objective ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Result / Issue</label>
                            <p class="text-sm">{{ $dailyActivity->result_of_issue ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
