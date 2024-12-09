@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('styles')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Updated color variables */
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1d4ed8;
        --secondary-color: #64748b;
        --success-color: #22c55e;
        --warning-color: #eab308;
        --danger-color: #ef4444;
        --background-color: #f1f5f9;
        --card-background: #ffffff;
    }

    .dashboard-container {
        padding: 1.5rem;
        background: var(--background-color);
        min-height: 100vh;
    }

    /* Enhanced header styles */
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .dashboard-header h1 {
        margin: 0;
        font-size: 2rem;
    }

    .quick-actions {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .action-btn {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--card-background);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .stat-card.primary::before { background: linear-gradient(to right, #1e90ff, #005cbf); }
    .stat-card.warning::before { background: linear-gradient(to right, #ffc107, #ff9800); }
    .stat-card.success::before { background: linear-gradient(to right, #4caf50, #2e7d32); }
    .stat-card.info::before { background: linear-gradient(to right, #00bcd4, #0097a7); }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #1e90ff, #005cbf);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .stat-icon.warning {
        background: linear-gradient(135deg, #ffc107, #ff9800);
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #4caf50, #2e7d32);
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #00bcd4, #0097a7);
    }

    .text-warning {
        color: #ffc107;
    }

    .text-success {
        color: #4caf50;
    }

    .alert-section {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .alert-section h3 {
        background: #f8f9fa;
        padding: 15px 20px;
        margin: 0;
        border-bottom: 1px solid #eee;
    }

    .alert-item {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    .alert-item:last-child {
        border-bottom: none;
    }

    .alert-action {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    .alert-action.warning {
        background: #ffc107;
        color: #000;
    }

    .alert-action.danger {
        background: #dc3545;
        color: #fff;
    }

    .alert-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .text-danger {
        color: #dc3545;
    }

    .text-warning {
        color: #ffc107;
    }

    .text-success {
        color: #28a745;
    }

    .department-overview {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .department-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .department-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    .dept-stats {
        margin-top: 10px;
    }

    .dept-stat {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .activity-feed {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .no-activities {
        text-align: center;
        color: #6c757d;
        padding: 20px;
    }

    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-container {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        height: 400px;
    }

    /* Value Summary */
    .value-summary {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .value-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 15px;
    }

    .value-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .value-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .value-amount {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e90ff;
    }

    /* Recent Transactions */
    .transactions {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .transaction-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s ease;
    }

    .transaction-item:hover {
        background-color: #f8f9fa;
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .transaction-icon.in {
        background: rgba(76, 175, 80, 0.1);
        color: #4caf50;
    }

    .transaction-icon.out {
        background: rgba(244, 67, 54, 0.1);
        color: #f44336;
    }

    /* New search styles */
    .search-container {
        margin-top: 1rem;
        max-width: 500px;
    }

    .search-form {
        width: 100%;
    }

    .search-input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem;
        padding-right: 3rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .search-button {
        position: absolute;
        right: 0.75rem;
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
        padding: 0.5rem;
        transition: color 0.3s ease;
    }

    .search-button:hover {
        color: rgba(255, 255, 255, 0.9);
    }

    /* New notification styles */
    .notification-section {
        margin-bottom: 2rem;
    }

    .notification-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: var(--card-background);
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .notification-item:hover {
        transform: translateY(-2px);
    }

    .notification-item.warning {
        border-left: 4px solid var(--warning-color);
    }

    .notification-item.danger {
        border-left: 4px solid var(--danger-color);
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .dashboard-header {
            padding: 1.5rem;
        }

        .quick-actions {
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-btn {
            width: 100%;
            text-align: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .value-grid {
            grid-template-columns: 1fr;
        }

        .transaction-item {
            flex-direction: column;
            text-align: center;
        }

        .transaction-icon {
            margin: 0 auto 1rem;
        }

        /* Enhanced touch targets */
        .action-btn, 
        .notification-close,
        .search-button {
            min-height: 44px; /* Minimum touch target size */
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card {
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
    }

    .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .activity-time {
        font-size: 0.875rem;
        color: #6b7280;
        min-width: 120px;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4b5563;
    }

    .activity-details {
        flex: 1;
    }

    .activity-description {
        margin-bottom: 0.25rem;
    }

    .activity-meta {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .activity-filters {
        max-width: 200px;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Dashboard Overview</h1>
            <p class="text-white-50">{{ now()->format('l, F j, Y') }}</p>
            
            <!-- Add search bar -->
            <div class="search-container">
                <form action="{{ route('admin.search') }}" method="GET" class="search-form">
                    <div class="search-input-container">
                        <input type="text" 
                               name="query" 
                               class="search-input" 
                               placeholder="Search items, departments..."
                               aria-label="Search"
                               value="{{ request('query') }}">
                        <button type="submit" class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Enhanced quick actions -->
        <div class="quick-actions">
            <a href="{{ route('admin.items.create') }}" class="action-btn">
                <i class="fas fa-plus"></i> Add Item
            </a>
            <a href="{{ route('admin.reports.generate') }}" class="action-btn">
                <i class="fas fa-file-export"></i> Export Report
            </a>
            <a href="{{ route('admin.inventory.scan') }}" class="action-btn">
                <i class="fas fa-barcode"></i> Scan Item
            </a>
        </div>
    </div>

    <!-- Add this after the header section -->
    <div class="notification-section">
        @if($notifications && count($notifications) > 0)
            @foreach($notifications as $notification)
                <div class="notification-item {{ $notification->type }}">
                    <i class="fas fa-{{ $notification->type === 'warning' ? 'exclamation-triangle' : 'info-circle' }}"></i>
                    <div class="notification-content">
                        <h4>{{ $notification->title }}</h4>
                        <p>{{ $notification->message }}</p>
                    </div>
                    <button class="notification-close" aria-label="Close notification">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i> 12%
                </div>
            </div>
            <h3>Total Items</h3>
            <div class="stat-value">{{ $totalItems }}</div>
            <div class="stat-label">Across all categories</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-trend negative">
                    <i class="fas fa-arrow-down"></i> 5%
                </div>
            </div>
            <h3>Low Stock</h3>
            <div class="stat-value">{{ $lowStockItems }}</div>
            <div class="stat-label">Items below minimum</div>
        </div>

        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i> 8%
                </div>
            </div>
            <h3>In Stock</h3>
            <div class="stat-value">{{ $inStockItems }}</div>
            <div class="stat-label">Available items</div>
        </div>

        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <h3>Departments</h3>
            <div class="stat-value">{{ $activeDepartments }}</div>
            <div class="stat-label">Active departments</div>
        </div>
    </div>

    <!-- Move this section up, after the stats-grid and before the charts-grid -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Activity Log</h3>
                    <div>
                        <select class="form-select form-select-sm d-inline-block w-auto me-2" onchange="filterActivities(this.value)">
                            <option value="all">All Activities</option>
                            <option value="stock_in">Stock In</option>
                            <option value="stock_out">Stock Out</option>
                            <option value="create">Created Items</option>
                            <option value="update">Updated Items</option>
                        </select>
                        <button class="btn btn-sm btn-primary" onclick="exportActivityLog()">
                            Export Log
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="activity-list">
                        @forelse($activityLogs as $log)
                        <div class="activity-item" data-type="{{ $log->action_type }}">
                            <div class="activity-time">
                                {{ $log->created_at->format('M d, Y H:i') }}
                            </div>
                            <div class="activity-icon">
                                <i class="fas fa-{{ $log->icon }}"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-description">{{ $log->description }}</div>
                                <div class="activity-meta">
                                    <span class="user">By: {{ $log->admin->name }}</span>
                                    @if($log->item)
                                        • <span class="item">Item: {{ $log->item->name }}</span>
                                    @endif
                                    @if($log->department)
                                        • <span class="department">Dept: {{ $log->department->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center text-muted">
                            No activities recorded yet
                        </div>
                        @endforelse
                    </div>
                    @if($activityLogs->hasPages())
                    <div class="card-footer">
                        {{ $activityLogs->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Stock Level Trends</h3>
                <div class="chart-actions">
                    <button class="btn btn-sm btn-outline-secondary" onclick="exportChart('stockTrendChart', 'png')">
                        <i class="fas fa-download"></i> PNG
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="exportChart('stockTrendChart', 'pdf')">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
            <canvas id="stockTrendChart"></canvas>
        </div>
        <div class="chart-container">
            <h3>Department Distribution</h3>
            <canvas id="departmentChart"></canvas>
        </div>
    </div>

    <!-- Inventory Value Summary -->
    <div class="value-summary">
        <h3>Inventory Value Summary</h3>
        <div class="value-grid">
            <div class="value-item">
                <div class="value-label">Total Inventory Value</div>
                <div class="value-amount">₱{{ number_format($totalValue ?? 0, 2) }}</div>
            </div>
            <div class="value-item">
                <div class="value-label">Low Stock Value</div>
                <div class="value-amount">₱{{ number_format($lowStockValue ?? 0, 2) }}</div>
            </div>
            <div class="value-item">
                <div class="value-label">Monthly Turnover</div>
                <div class="value-amount">₱{{ number_format($monthlyTurnover ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Stock Movements -->
    <div class="transactions">
        <h3>Recent Stock Movements</h3>
        @forelse($recentMovements ?? [] as $movement)
        <div class="transaction-item">
            <div class="transaction-icon {{ $movement->type }}">
                <i class="fas fa-{{ $movement->type === 'in' ? 'plus' : 'minus' }}"></i>
            </div>
            <div class="transaction-details">
                <div class="transaction-title">
                    {{ $movement->type === 'in' ? 'Stock Added' : 'Stock Removed' }} - {{ $movement->item->name }}
                </div>
                <div class="transaction-meta">
                    {{ $movement->quantity }} units {{ $movement->type === 'in' ? 'added to' : 'removed from' }} inventory
                    <span class="text-muted">• {{ $movement->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted text-center py-3">No recent stock movements</p>
        @endforelse
    </div>
</div>

@endsection

@section('scripts')
<script>
// Enhanced Stock Trend Chart
const stockTrendCtx = document.getElementById('stockTrendChart').getContext('2d');
new Chart(stockTrendCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Stock Levels',
            data: [65, 59, 80, 81, 56, 55],
            borderColor: 'rgb(37, 99, 235)',
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(37, 99, 235, 0.1)'
        }]
    },
    options: {
        responsive: true,
        interaction: {
            intersect: false,
            mode: 'index',
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                titleColor: '#1e293b',
                bodyColor: '#475569',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                padding: 10,
                boxPadding: 5
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Department Distribution Chart
const departmentCtx = document.getElementById('departmentChart').getContext('2d');
new Chart(departmentCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($departmentStats->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($departmentStats->pluck('total_items')) !!},
            backgroundColor: [
                '#2563eb', // Primary blue
                '#22c55e', // Success green
                '#eab308', // Warning yellow
                '#ef4444', // Danger red
                '#8b5cf6', // Purple
                '#ec4899', // Pink
                '#14b8a6'  // Teal
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    padding: 20,
                    boxWidth: 12,
                    font: {
                        size: 13
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                titleColor: '#1e293b',
                bodyColor: '#475569',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                padding: 10,
                boxPadding: 5,
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Add notification close functionality
document.querySelectorAll('.notification-close').forEach(button => {
    button.addEventListener('click', function() {
        this.closest('.notification-item').remove();
    });
});

// Filter functionality
document.querySelectorAll('[data-filter]').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Get filter value
        const filter = this.dataset.filter;
        
        // Update dashboard data based on filter
        fetchFilteredData(filter);
    });
});

function fetchFilteredData(filter) {
    fetch(`/admin/dashboard/filter?type=${filter}`)
        .then(response => response.json())
        .then(data => {
            updateDashboardData(data);
        });
}

function updateDashboardData(data) {
    // Update statistics
    document.querySelector('.stat-value').textContent = data.totalItems;
    // ... update other stats
    
    // Update charts
    stockTrendChart.data = data.stockTrends;
    stockTrendChart.update();
    
    departmentChart.data = data.departmentDistribution;
    departmentChart.update();
}

function exportChart(chartId, format) {
    const canvas = document.getElementById(chartId);
    
    if (format === 'png') {
        // Download as PNG
        const link = document.createElement('a');
        link.download = `${chartId}-export.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    } else if (format === 'pdf') {
        // Create PDF using jsPDF
        const pdf = new jsPDF();
        const imgData = canvas.toDataURL('image/png');
        pdf.addImage(imgData, 'PNG', 10, 10);
        pdf.save(`${chartId}-export.pdf`);
    }
}
</script>
@endsection