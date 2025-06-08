@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Top Events -->
        <section class="bg-white rounded-lg shadow p-6 flex flex-col">
            <h2 class="text-xl font-semibold text-gray-800 mb-5 border-b border-gray-200 pb-2">
                Top Events
            </h2>
            <div class="h-64">
                <canvas id="eventSalesChart" class="w-full h-full"></canvas>
            </div>
        </section>

        <!-- Monthly Sales -->
        <section class="bg-white rounded-lg shadow p-6 flex flex-col">
            <h2 class="text-xl font-semibold text-gray-800 mb-5 border-b border-gray-200 pb-2">
                Monthly Sales
            </h2>
            <div class="h-64">
                <canvas id="monthlySalesChart" class="w-full h-full"></canvas>
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Monthly Revenue -->
        <section class="bg-white rounded-lg shadow p-6 flex flex-col">
            <h2 class="text-xl font-semibold text-gray-800 mb-5 border-b border-gray-200 pb-2">
                Monthly Revenue
            </h2>
            <div class="h-64">
                <canvas id="monthlyRevenueChart" class="w-full h-full"></canvas>
            </div>
        </section>

        <!-- Order Statuses -->
        <section class="bg-white rounded-lg shadow p-6 flex flex-col">
            <h2 class="text-xl font-semibold text-gray-800 mb-5 border-b border-gray-200 pb-2">
                Order Statuses
            </h2>
            <div class="h-64">
                <canvas id="orderStatusChart" class="w-full h-full"></canvas>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Top Events Chart
    new Chart(document.getElementById('eventSalesChart'), {
        type: 'bar',
        data: {
            labels: @json($eventLabels),
            datasets: [{
                label: 'Tickets Sold',
                data: @json($eventData),
                backgroundColor: 'rgba(79, 70, 229, 0.7)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    // Monthly Sales Chart
    new Chart(document.getElementById('monthlySalesChart'), {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Ticket Quantity',
                data: @json($monthlyTicketsData),
                borderColor: 'rgba(59, 130, 246, 1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true },
                x: { title: { display: true, text: 'Month' } }
            }
        }
    });

    // Monthly Revenue Chart
    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Revenue (PLN)',
                data: @json($monthlyRevenueData),
                backgroundColor: 'rgba(16, 185, 129, 0.7)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Amount (PLN)' }
                },
                x: { title: { display: true, text: 'Month' } }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw.toFixed(2) + ' PLN';
                        }
                    }
                }
            }
        }
    });

    // Order Statuses Chart
    new Chart(document.getElementById('orderStatusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{
                data: @json($statusData),
                backgroundColor: [
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(239, 68, 68, 0.7)'
                ],
                borderColor: [
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw;
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
