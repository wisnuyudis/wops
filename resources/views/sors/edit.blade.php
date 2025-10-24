@extends('layouts.app')

@section('page-title', 'Edit SOR')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Edit SOR</h6>
                    <a href="{{ route('sors.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('sors.update', $sor) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sor" class="form-control-label">SOR Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sor') is-invalid @enderror" 
                                       id="sor" name="sor" value="{{ old('sor', $sor->sor) }}" required>
                                @error('sor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="init_date" class="form-control-label">Init Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('init_date') is-invalid @enderror" 
                                       id="init_date" name="init_date" value="{{ old('init_date', $sor->init_date->format('Y-m-d')) }}" required>
                                @error('init_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_id" class="form-control-label">Customer <span class="text-danger">*</span></label>
                                <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $sor->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_id" class="form-control-label">Product <span class="text-danger">*</span></label>
                                <select class="form-control @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $sor->product_id) == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="status" class="form-control-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status', $sor->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $sor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-control-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5">{{ old('description', $sor->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('sors.index') }}" class="btn btn-light m-0 me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary m-0">Update SOR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
