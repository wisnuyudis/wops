@extends('layouts.app')

@section('page-title', 'SOR Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>SOR (Statement Of Requirements) List</h6>
                    <a href="{{ route('sors.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New SOR
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SOR</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Product</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Init Date</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sors as $sor)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $sor->sor }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ Str::limit($sor->description, 50) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $sor->customer->name }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $sor->product->name }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $sor->init_date->format('d M Y') }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="badge badge-sm bg-gradient-{{ $sor->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($sor->status) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('sors.show', $sor) }}" class="text-info font-weight-bold text-xs me-2">
                                        View
                                    </a>
                                    <a href="{{ route('sors.edit', $sor) }}" class="text-secondary font-weight-bold text-xs me-2">
                                        Edit
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('sors.destroy', $sor) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this SOR?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger font-weight-bold text-xs border-0 bg-transparent" style="cursor: pointer;">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No SORs found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 mt-3">
                    {{ $sors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
