@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label text-xs mb-1">Select Period</label>
                            <input type="month" name="month" class="form-control form-control-sm" 
                                   value="{{ $selectedMonth }}" onchange="this.form.submit()">
                        </div>
                        @if(auth()->user()->role === 'admin')
                        <div class="col-md-4">
                            <label class="form-label text-xs mb-1">Select User</label>
                            <select name="user_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <!-- Pie Chart - Activity by Customers -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Daily Activity by Customers</h6>
                <p class="text-sm mb-0">Distribution of activities per customer</p>
            </div>
            <div class="card-body">
                <canvas id="customerChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Bar Chart - Activity by Product -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Daily Activity by Product</h6>
                <p class="text-sm mb-0">Number of activities per product</p>
            </div>
            <div class="card-body">
                <canvas id="productChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Line Chart - Activity by Job Items -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Daily Activity by Job Items</h6>
                <p class="text-sm mb-0">Distribution of activities per job item</p>
            </div>
            <div class="card-body">
                <canvas id="jobItemChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
// Register datalabels plugin
Chart.register(ChartDataLabels);

// Pie Chart - Customers
const customerData = {
    labels: {!! json_encode($activityByCustomers->pluck('cust_name')) !!},
    datasets: [{
        data: {!! json_encode($activityByCustomers->pluck('total')) !!},
        backgroundColor: [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
        ]
    }]
};

const customerChart = new Chart(document.getElementById('customerChart'), {
    type: 'pie',
    data: customerData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    font: { size: 10 }
                }
            },
            datalabels: {
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 12
                },
                formatter: (value) => value
            }
        }
    }
});

// Bar Chart - Products
const productData = {
    labels: {!! json_encode($activityByProducts->pluck('product')) !!},
    datasets: [{
        label: 'Activities',
        data: {!! json_encode($activityByProducts->pluck('total')) !!},
        backgroundColor: '#36A2EB'
    }]
};

const productChart = new Chart(document.getElementById('productChart'), {
    type: 'bar',
    data: productData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        },
        plugins: {
            legend: { display: false },
            datalabels: {
                anchor: 'end',
                align: 'top',
                color: '#444',
                font: {
                    weight: 'bold',
                    size: 11
                },
                formatter: (value) => value
            }
        }
    }
});

// Pie Chart - Job Items
const jobItemData = {
    labels: {!! json_encode($activityByJobItems->pluck('name')) !!},
    datasets: [{
        data: {!! json_encode($activityByJobItems->pluck('total')) !!},
        backgroundColor: [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#E7E9ED', '#C9CBCF', '#4BC0C0', '#FF6384'
        ]
    }]
};

const jobItemChart = new Chart(document.getElementById('jobItemChart'), {
    type: 'pie',
    data: jobItemData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    font: { size: 10 }
                }
            },
            datalabels: {
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 12
                },
                formatter: (value) => value
            }
        }
    }
});
</script>
@endpush
