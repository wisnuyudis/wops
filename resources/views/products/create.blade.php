@extends('layouts.app')

@section('page-title', 'Create Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Create New Product</h6>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-control-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-control-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-light m-0 me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary m-0">Create Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
