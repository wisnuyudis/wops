@extends('layouts.app')

@section('page-title', 'Create Daily Activity')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Create New Daily Activity</h6>
                    <a href="{{ route('daily-activities.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('daily-activities.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date" class="form-control-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sor_id" class="form-control-label">SOR <small class="text-muted">(Optional - untuk exploration/research)</small></label>
                                <select class="form-control @error('sor_id') is-invalid @enderror" id="sor_id" name="sor_id">
                                    <option value="">Select SOR (Optional)</option>
                                    @foreach($sors as $sor)
                                        <option value="{{ $sor->id }}" {{ old('sor_id') == $sor->id ? 'selected' : '' }}>
                                            {{ $sor->sor }} - {{ $sor->customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cust_name" class="form-control-label">Customer Name <small class="text-muted">(Manual entry)</small></label>
                                <input type="text" class="form-control @error('cust_name') is-invalid @enderror" 
                                       id="cust_name" name="cust_name" value="{{ old('cust_name') }}">
                                @error('cust_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Will use SOR customer if left blank</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product" class="form-control-label">Product</label>
                                <input type="text" class="form-control @error('product') is-invalid @enderror" 
                                       id="product" name="product" value="{{ old('product') }}">
                                @error('product')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pic" class="form-control-label">PIC</label>
                                <input type="text" class="form-control @error('pic') is-invalid @enderror" 
                                       id="pic" name="pic" value="{{ old('pic') }}">
                                @error('pic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-control-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="job_type_id" class="form-control-label">Job Type</label>
                                <select class="form-control @error('job_type_id') is-invalid @enderror" id="job_type_id" name="job_type_id">
                                    <option value="">Select Job Type</option>
                                    @foreach($jobTypes as $jobType)
                                        <option value="{{ $jobType->id }}" {{ old('job_type_id') == $jobType->id ? 'selected' : '' }}>
                                            {{ $jobType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="job_item_id" class="form-control-label">Job Item</label>
                                <select class="form-control @error('job_item_id') is-invalid @enderror" id="job_item_id" name="job_item_id">
                                    <option value="">Select Job Item</option>
                                    @foreach($jobItems as $jobItem)
                                        <option value="{{ $jobItem->id }}" {{ old('job_item_id') == $jobItem->id ? 'selected' : '' }}>
                                            {{ $jobItem->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="action" class="form-control-label">Action</label>
                        <textarea class="form-control @error('action') is-invalid @enderror" 
                                  id="action" name="action" rows="3">{{ old('action') }}</textarea>
                        @error('action')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="objective" class="form-control-label">Objective</label>
                        <textarea class="form-control @error('objective') is-invalid @enderror" 
                                  id="objective" name="objective" rows="3">{{ old('objective') }}</textarea>
                        @error('objective')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="result_of_issue" class="form-control-label">Result / Issue</label>
                        <textarea class="form-control @error('result_of_issue') is-invalid @enderror" 
                                  id="result_of_issue" name="result_of_issue" rows="3">{{ old('result_of_issue') }}</textarea>
                        @error('result_of_issue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('daily-activities.index') }}" class="btn btn-light m-0 me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary m-0">Create Activity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
