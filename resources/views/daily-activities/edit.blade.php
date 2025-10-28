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
                                <label for="cust_name" class="form-control-label">Customer Name <small class="text-muted">(Manual entry with autocomplete)</small></label>
                                <input type="text" class="form-control @error('cust_name') is-invalid @enderror" 
                                       id="cust_name" name="cust_name" value="{{ old('cust_name', $dailyActivity->cust_name) }}"
                                       list="customerList" placeholder="Type or select customer">
                                <datalist id="customerList">
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->name }}">
                                    @endforeach
                                </datalist>
                                @error('cust_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product" class="form-control-label">Product <small class="text-muted">(Manual entry with autocomplete)</small></label>
                                <input type="text" class="form-control @error('product') is-invalid @enderror" 
                                       id="product" name="product" value="{{ old('product', $dailyActivity->product) }}" 
                                       list="productList" placeholder="Type or select product">
                                <datalist id="productList">
                                    @foreach($products as $product)
                                        <option value="{{ $product->name }}">
                                    @endforeach
                                </datalist>
                                @error('product')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Will use SOR product if left blank and SOR is selected</small>
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
                                <label class="form-control-label d-block">Status <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_pending" 
                                               value="pending" {{ old('status', $dailyActivity->status) == 'pending' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status_pending">
                                            Pending
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_in_progress" 
                                               value="in_progress" {{ old('status', $dailyActivity->status) == 'in_progress' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_in_progress">
                                            In Progress
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_completed" 
                                               value="completed" {{ old('status', $dailyActivity->status) == 'completed' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_completed">
                                            Completed
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_on_hold" 
                                               value="on_hold" {{ old('status', $dailyActivity->status) == 'on_hold' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_on_hold">
                                            On Hold
                                        </label>
                                    </div>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="job_type_id" class="form-control-label">Job Type</label>
                                <select class="form-control select2 @error('job_type_id') is-invalid @enderror" id="job_type_id" name="job_type_id">
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
                                <select class="form-control select2 @error('job_item_id') is-invalid @enderror" id="job_item_id" name="job_item_id">
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 42px;
        padding: 6px 12px;
        border: 1px solid #d2d6da;
        border-radius: 0.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px;
        color: #495057;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    .select2-dropdown {
        border: 1px solid #d2d6da;
        border-radius: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for Job Type and Job Item
    $('.select2').select2({
        placeholder: 'Search and select...',
        allowClear: true,
        width: '100%'
    });
    
    const sorSelect = document.getElementById('sor_id');
    const custNameInput = document.getElementById('cust_name');
    const productInput = document.getElementById('product');
    
    sorSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            // Always update customer and product from SOR when SOR changes
            const customer = selectedOption.getAttribute('data-customer');
            const product = selectedOption.getAttribute('data-product');
            
            custNameInput.value = customer || '';
            productInput.value = product || '';
        } else {
            // Clear fields if no SOR selected
            custNameInput.value = '';
            productInput.value = '';
        }
    });
});
</script>
@endpush
