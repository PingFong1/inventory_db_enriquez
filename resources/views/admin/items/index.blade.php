@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('styles')
<style>
    /* Variables */
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1d4ed8;
        --secondary-color: #64748b;
        --success-color: #22c55e;
        --warning-color: #eab308;
        --danger-color: #ef4444;
        --info-color: #0ea5e9;
        --background-color: #f1f5f9;
        --card-background: #ffffff;
    }

    /* Layout */
    .inventory-container {
        padding: 1.5rem;
        background: var(--background-color);
        min-height: 100vh;
    }

    /* Header Section */
    .header-section {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 2rem;
        border-radius: 0.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .header-title {
        font-size: 1.75rem;
        font-weight: 600;
        margin: 0;
    }

    .header-date {
        font-size: 0.875rem;
        opacity: 0.8;
    }

    .new-item-btn {
        background: var(--success-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .new-item-btn:hover {
        background: #16a34a;
        transform: translateY(-1px);
    }

    /* Search Section */
    .search-section {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: center;
    }

    .search-input {
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        width: 100%;
        transition: all 0.2s;
    }

    .search-input:focus {
        background: rgba(255, 255, 255, 0.2);
        outline: none;
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .filter-btn {
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 0.5rem;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .filter-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--card-background);
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        color: var(--primary-color);
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2f2f2f;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: var(--secondary-color);
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Table Styles */
    .items-table {
        width: 100%;
        background: var(--card-background);
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .items-table th {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 500;
        text-align: left;
        white-space: nowrap;
    }

    .items-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .items-table tbody tr:hover {
        background: var(--background-color);
    }

    .item-name {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    /* Status badges */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .in-stock {
        background: #dcfce7;
        color: #166534;
    }

    .low-stock {
        background: #fef3c7;
        color: #92400e;
    }

    /* Quantity controls */
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-quantity {
        width: 28px;
        height: 28px;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-quantity:hover {
        background: var(--background-color);
        border-color: #ced4da;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        color: white;
    }

    .btn-view { background: var(--info-color); }
    .btn-edit { background: var(--primary-color); }
    .btn-print { background: var(--warning-color); }
    .btn-delete { background: var(--danger-color); }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .search-section {
            grid-template-columns: 1fr;
        }

        .items-table {
            display: block;
            overflow-x: auto;
        }
    }

    .btn-loading {
        opacity: 0.7;
        pointer-events: none;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .delete-selected-btn {
        background: var(--danger-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .delete-selected-btn:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .select-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Add to your existing styles */
    .filter-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        min-width: 250px;
        z-index: 1000;
        display: none;
    }

    .filter-dropdown.show {
        display: block;
        animation: fadeIn 0.2s ease-out;
    }

    .filter-group {
        margin-bottom: 1rem;
    }

    .filter-group:last-child {
        margin-bottom: 0;
    }

    .filter-label {
        display: block;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #4a5568;
    }

    .filter-select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .filter-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-filter {
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-apply {
        background: var(--primary-color);
        color: white;
        border: none;
    }

    .btn-reset {
        background: none;
        border: 1px solid #e2e8f0;
        color: var(--secondary-color);
    }

    .btn-filter:hover {
        transform: translateY(-1px);
    }

    /* Pagination Styles */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        gap: 0.5rem;
    }

    .pagination-btn {
        padding: 0.5rem 1rem;
        border: 1px solid var(--primary-color);
        border-radius: 0.25rem;
        color: var(--primary-color);
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination-btn:hover {
        background: var(--primary-color);
        color: white;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')
<div class="inventory-container">
    <div class="header-section">
        <div class="header-content">
            <div>
                <h1 class="header-title">Inventory Management</h1>
                <p class="header-date">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="header-actions">
                <button id="deleteSelected" class="delete-selected-btn" style="display: none;">
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
                <a href="{{ route('admin.items.create') }}" class="new-item-btn">
                    <i class="fas fa-plus"></i> New Item
                </a>
            </div>
        </div>
        <div class="search-section">
            <input type="text" class="search-input" placeholder="Search inventory items...">
            <div style="position: relative;">
                <button class="filter-btn" id="filterBtn">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <div class="filter-dropdown" id="filterDropdown">
                    <form id="filterForm">
                        <div class="filter-group">
                            <label class="filter-label">Category</label>
                            <select class="filter-select" name="category">
                                <option value="">All Categories</option>
                                <option value="cleaning" {{ request('category') === 'cleaning' ? 'selected' : '' }}>Cleaning Supplies</option>
                                <option value="maintenance" {{ request('category') === 'maintenance' ? 'selected' : '' }}>Maintenance Tools</option>
                                <option value="safety" {{ request('category') === 'safety' ? 'selected' : '' }}>Safety Equipment</option>
                                <option value="furniture" {{ request('category') === 'furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="electronics" {{ request('category') === 'electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="office" {{ request('category') === 'office' ? 'selected' : '' }}>Office Supplies</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select class="filter-select" name="status">
                                <option value="">All Status</option>
                                <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Sort By</label>
                            <select class="filter-select" name="sort">
                                <option value="">Default</option>
                                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                <option value="quantity_asc" {{ request('sort') === 'quantity_asc' ? 'selected' : '' }}>Quantity (Low to High)</option>
                                <option value="quantity_desc" {{ request('sort') === 'quantity_desc' ? 'selected' : '' }}>Quantity (High to Low)</option>
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button type="button" class="btn-filter btn-reset" id="resetFilters">Reset</button>
                            <button type="submit" class="btn-filter btn-apply">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-value">{{ $totalItems ?? 0 }}</div>
            <div class="stat-label">Total Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $lowStockItems ?? 0 }}</div>
            <div class="stat-label">Low Stock Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $inStockItems ?? 0 }}</div>
            <div class="stat-label">Items In Stock</div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="selectAll" class="select-checkbox">
                </th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>
                    <input type="checkbox" class="select-checkbox item-checkbox" value="{{ $item->id }}">
                </td>
                <td>
                    <a href="{{ route('admin.items.show', $item) }}" class="item-name">
                        {{ $item->name }}
                    </a>
                </td>
                <td>{{ $item->category }}</td>
                <td>
                    <div class="quantity-controls">
                        <button class="btn-quantity" onclick="decrementQuantity({{ $item->id }})">-</button>
                        <span>{{ $item->current_quantity }}</span>
                        <button class="btn-quantity" onclick="incrementQuantity({{ $item->id }})">+</button>
                    </div>
                </td>
                <td>
                    <span class="status-badge {{ $item->current_quantity <= $item->minimum_quantity ? 'low-stock' : 'in-stock' }}">
                        {{ $item->current_quantity <= $item->minimum_quantity ? 'Low Stock' : 'In Stock' }}
                    </span>
                </td>
                <td>{{ $item->updated_at->format('M d, Y') }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.items.show', $item) }}" class="btn-action btn-view" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.items.edit', $item) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn-action btn-print" title="Print" onclick="printItem({{ $item->id }})">
                            <i class="fas fa-print"></i>
                        </button>
                        <button class="btn-action btn-delete" title="Delete" onclick="confirmDelete({{ $item->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">No items found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        @if($items->previousPageUrl())
            <a href="{{ $items->previousPageUrl() }}" class="pagination-btn">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
        @else
            <button class="pagination-btn" disabled>
                <i class="fas fa-chevron-left"></i> Previous
            </button>
        @endif

        @if($items->nextPageUrl())
            <a href="{{ $items->nextPageUrl() }}" class="pagination-btn">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <button class="pagination-btn" disabled>
                Next <i class="fas fa-chevron-right"></i>
            </button>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function incrementQuantity(itemId) {
    fetch(`/admin/items/${itemId}/increment`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Error updating quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating quantity');
    });
}

function decrementQuantity(itemId) {
    fetch(`/admin/items/${itemId}/decrement`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Error updating quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating quantity');
    });
}

function printItem(itemId) {
    window.open(`/admin/items/${itemId}/print`, '_blank');
}

function confirmDelete(itemId) {
    if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
        // Create a hidden form to submit the DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/items/${itemId}`;
        form.style.display = 'none';

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add method spoofing
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        document.body.appendChild(form);
        form.submit();
    }
}

// Add search functionality
const searchInput = document.querySelector('.search-input');
searchInput.addEventListener('input', debounce(function(e) {
    const searchTerm = e.target.value;
    window.location.href = `{{ route('admin.items.index') }}?search=${searchTerm}`;
}, 500));

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

window.addEventListener('error', function(e) {
    console.error('Global error:', e);
    alert('An unexpected error occurred. Please try again.');
});

// Multiple deletion functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const deleteSelectedBtn = document.getElementById('deleteSelected');
    
    // Handle "Select All" checkbox
    selectAll.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateDeleteButtonVisibility();
    });

    // Handle individual checkboxes
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateDeleteButtonVisibility();
            
            // Update "Select All" checkbox
            const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
            selectAll.checked = allChecked;
            selectAll.indeterminate = someChecked && !allChecked;
        });
    });

    // Update delete button visibility
    function updateDeleteButtonVisibility() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        deleteSelectedBtn.style.display = checkedBoxes.length > 0 ? 'flex' : 'none';
    }

    // Handle multiple deletion
    deleteSelectedBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.item-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) return;

        if (confirm(`Are you sure you want to delete ${selectedIds.length} selected items? This action cannot be undone.`)) {
            fetch(`{{ route('admin.items.bulk-delete') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error deleting items');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting items');
            });
        }
    });
});

// Add to your existing scripts section
document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...

    // Filter functionality
    const filterBtn = document.getElementById('filterBtn');
    const filterDropdown = document.getElementById('filterDropdown');
    const filterForm = document.getElementById('filterForm');
    const resetFilters = document.getElementById('resetFilters');

    // Toggle filter dropdown
    filterBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        filterDropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!filterDropdown.contains(e.target) && !filterBtn.contains(e.target)) {
            filterDropdown.classList.remove('show');
        }
    });

    // Handle filter form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }

        // Add existing search term if any
        const searchTerm = searchInput.value;
        if (searchTerm) {
            params.append('search', searchTerm);
        }

        window.location.href = `{{ route('admin.items.index') }}?${params.toString()}`;
    });

    // Reset filters
    resetFilters.addEventListener('click', function() {
        window.location.href = '{{ route('admin.items.index') }}';
    });
});
</script>
@endsection