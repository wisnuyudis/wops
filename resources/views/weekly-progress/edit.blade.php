@extends('layouts.app')

@section('page-title', 'Edit Weekly Progress')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Edit Weekly Progress</h6>
                    <a href="{{ route('weekly-progress.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                
                <form action="{{ route('weekly-progress.update', $weeklyProgress) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year" class="form-control-label">Year <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                       id="year" name="year" value="{{ old('year', $weeklyProgress->year) }}" required>
                                @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="week_number" class="form-control-label">Week Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('week_number') is-invalid @enderror" 
                                       id="week_number" name="week_number" value="{{ old('week_number', $weeklyProgress->week_number) }}" 
                                       min="1" max="53" required>
                                @error('week_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @php
                                    $currentDate = new DateTime();
                                    $currentDate->setISODate(date('Y'), date('W'));
                                    $currentDate->modify('Monday this week');
                                    $weekStart = $currentDate->format('d M');
                                    $currentDate->modify('+4 days');
                                    $weekEnd = $currentDate->format('d M Y');
                                @endphp
                                <small class="form-text text-muted">Current week: {{ date('W') }} ({{ $weekStart }} - {{ $weekEnd }})</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="last_week_status" class="form-control-label">Last Week Status <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('last_week_status') is-invalid @enderror" 
                                          id="last_week_status" name="last_week_status" rows="3" required>{{ old('last_week_status', $weeklyProgress->last_week_status) }}</textarea>
                                @error('last_week_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="p1" class="form-control-label">P1 (Must Complete This Week) <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('p1') is-invalid @enderror" 
                                          id="p1" name="p1" rows="3" required>{{ old('p1', $weeklyProgress->p1) }}</textarea>
                                @error('p1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="p2" class="form-control-label">P2 (Secondary Priority)</label>
                                <textarea class="form-control @error('p2') is-invalid @enderror" 
                                          id="p2" name="p2" rows="3">{{ old('p2', $weeklyProgress->p2) }}</textarea>
                                @error('p2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="p3" class="form-control-label">P3 (2+ Weeks Ahead)</label>
                                <textarea class="form-control @error('p3') is-invalid @enderror" 
                                          id="p3" name="p3" rows="3">{{ old('p3', $weeklyProgress->p3) }}</textarea>
                                @error('p3')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
