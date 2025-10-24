@extends('layouts.app')

@section('page-title', 'Edit Daily Activity')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Edit Daily Activity</h6>
                    <a href="{{ route('daily-activities.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('daily-activities.update', $dailyActivity) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date" class="form-control-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', $dailyActivity->date->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sor_id" class="form-control-label">SOR <small class="text-muted">(Optional)</small></label>
                                <select class="form-control @error('sor_id') is-invalid @enderror" id="sor_id" name="sor_id">
                                    <option value="">Select SOR (Optional)</option>
                                    @foreach($sors as $sor)
                                        <option value="{{ $sor->id }}" 
                                                data-customer="{{ $sor->customer->name }}"
                                                data-product="{{ $sor->product->name }}"
                                                {{ old('sor_id', $dailyActivity->sor_id) == $sor->id ? 'selected' : '' }}>
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
                                <label for="cust_name" class="form-control-label">Customer Name</label>
                                <input type="text" class="form-control @error('cust_name') is-invalid @enderror" 
                                       id="cust_name" name="cust_name" value="{{ old('cust_name', $dailyActivity->cust_name) }}">
                                @error('cust_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product" class="form-control-label">Product</label>
                                <input type="text" class="form-control @error('product') is-invalid @enderror" 
                                       id="product" name="product" value="{{ old('product', $dailyActivity->product) }}" readonly>
                                @error('product')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Auto-filled from SOR</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pic" class="form-control-label">PIC</label>
                                <input type="text" class="form-control @error('pic') is-invalid @enderror" 
                                       id="pic" name="pic" value="{{ old('pic', $dailyActivity->pic) }}">
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
                                    <option value="pending" {{ old('status', $dailyActivity->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $dailyActivity->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $dailyActivity->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ old('status', $dailyActivity->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
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
                                        <option value="{{ $jobType->id }}" {{ old('job_type_id', $dailyActivity->job_type_id) == $jobType->id ? 'selected' : '' }}>
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
                                        <option value="{{ $jobItem->id }}" {{ old('job_item_id', $dailyActivity->job_item_id) == $jobItem->id ? 'selected' : '' }}>
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
                        <label for="action" class="form-control-label">Action <span class="text-danger">*</span></label>
                        <select class="form-control @error('action') is-invalid @enderror" id="action" name="action" required>
                            <option value="">Select Action</option>
                            <option value="Cust Call" {{ old('action', $dailyActivity->action) == 'Cust Call' ? 'selected' : '' }}>Cust Call</option>
                            <option value="Online Meet" {{ old('action', $dailyActivity->action) == 'Online Meet' ? 'selected' : '' }}>Online Meet</option>
                            <option value="Remote" {{ old('action', $dailyActivity->action) == 'Remote' ? 'selected' : '' }}>Remote</option>
                            <option value="Visit" {{ old('action', $dailyActivity->action) == 'Visit' ? 'selected' : '' }}>Visit</option>
                            <option value="sdt Internal" {{ old('action', $dailyActivity->action) == 'sdt Internal' ? 'selected' : '' }}>sdt Internal</option>
                            <option value="Event" {{ old('action', $dailyActivity->action) == 'Event' ? 'selected' : '' }}>Event</option>
                            <option value="Training" {{ old('action', $dailyActivity->action) == 'Training' ? 'selected' : '' }}>Training</option>
                        </select>
                        @error('action')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="objective" class="form-control-label">Objective</label>
                        <textarea class="form-control @error('objective') is-invalid @enderror" 
                                  id="objective" name="objective" rows="3">{{ old('objective', $dailyActivity->objective) }}</textarea>
                        @error('objective')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="result_of_issue" class="form-control-label">Result / Issue</label>
                        <textarea class="form-control @error('result_of_issue') is-invalid @enderror" 
                                  id="result_of_issue" name="result_of_issue" rows="3">{{ old('result_of_issue', $dailyActivity->result_of_issue) }}</textarea>
                        @error('result_of_issue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('daily-activities.index') }}" class="btn btn-light m-0 me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary m-0">Update Activity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sorSelect = document.getElementById('sor_id');
    const custNameInput = document.getElementById('cust_name');
    const productInput = document.getElementById('product');
    
    sorSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            // Auto-fill customer and product from SOR
            const customer = selectedOption.getAttribute('data-customer');
            const product = selectedOption.getAttribute('data-product');
            
            custNameInput.value = customer;
            productInput.value = product;
        } else {
            // Clear fields if no SOR selected
            custNameInput.value = '';
            productInput.value = '';
        }
    });
});
</script>
@endpush
