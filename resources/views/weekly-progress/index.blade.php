@extends('layouts.app')

@section('page-title', 'Weekly Progress')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Weekly Progress List</h6>
                    <a href="{{ route('weekly-progress.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                @if(session('success'))
                <div class="alert alert-success mx-4 mt-3">
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('info'))
                <div class="alert alert-info mx-4 mt-3">
                    {{ session('info') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger mx-4 mt-3">
                    {{ session('error') }}
                </div>
                @endif
                
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Year</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Week Period</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Week Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($weeklyProgresses as $progress)
                            @php
                                $date = new DateTime();
                                $date->setISODate($progress->year, $progress->week_number);
                                $weekStart = $date->format('d M');
                                $date->modify('+6 days');
                                $weekEnd = $date->format('d M Y');
                            @endphp
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 ms-3">{{ $progress->year }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">Week {{ $progress->week_number }}<br><small class="text-muted">{{ $weekStart }} - {{ $weekEnd }}</small></p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $progress->user->name }}</p>
                                </td>
                                <td>
                                    <p class="text-xs mb-0">{{ Str::limit($progress->last_week_status, 50) }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('weekly-progress.show', $progress) }}" class="btn btn-link text-secondary mb-0" title="View">
                                        <i class="fa fa-eye text-xs"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin' || $progress->user_id === auth()->id())
                                    <a href="{{ route('weekly-progress.edit', $progress) }}" class="btn btn-link text-secondary mb-0" title="Edit">
                                        <i class="fa fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('weekly-progress.destroy', $progress) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-secondary mb-0" onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="fa fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <p class="text-xs text-secondary mb-0">No weekly progress found.</p>
                                </td>
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
