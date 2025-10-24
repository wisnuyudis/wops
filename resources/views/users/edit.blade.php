@extends('layouts.app')

@section('page-title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Edit User</h6>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="form-control-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-control-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Leave blank to keep current password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave blank if you don't want to change the password</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="job_id" class="form-control-label">Job Position</label>
                                <select class="form-control @error('job_id') is-invalid @enderror" id="job_id" name="job_id">
                                    <option value="">Select Job</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}" {{ old('job_id', $user->job_id) == $job->id ? 'selected' : '' }}>
                                            {{ $job->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="form-control-label">Role <span class="text-danger">*</span></label>
                                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-control-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-light m-0 me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary m-0">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
