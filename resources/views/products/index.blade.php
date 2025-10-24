@extends('layouts.app')

@section('page-title', 'Product Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Products List</h6>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                    @endif
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $product->description ?? '-' }}</p>
                                </td>
                                <td class="align-middle">
                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('products.edit', $product) }}" class="text-secondary font-weight-bold text-xs me-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                                <td colspan="3" class="text-center">No products found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
