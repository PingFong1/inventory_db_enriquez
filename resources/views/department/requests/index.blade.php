@extends('layouts.app')

@section('title', 'My Requests')

@section('styles')
<style>
    .requests-container {
        background: var(--card-background);
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .request-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .request-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-pending {
        background-color: var(--warning-color);
        color: white;
    }

    .status-approved {
        background-color: var(--success-color);
        color: white;
    }

    .status-declined {
        background-color: var(--danger-color);
        color: white;
    }

    .request-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 0.875rem;
        color: var(--secondary-color);
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-weight: 500;
    }

    .actions {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }

    .request-form {
        background: var(--card-background);
        border-radius: 0.5rem;
        padding: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* Add new search-specific styles */
    .search-container {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-color);
    }

    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    .suggestion-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .suggestion-item:hover {
        background: var(--hover-color);
    }

    .suggestion-thumb {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 0.25rem;
    }

    /* Add form styling from create.blade.php */
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 500;
        color: #4a5568;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 14px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        outline: none;
    }

    .form-actions {
        display: flex;
        gap: 20px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #eef2f7;
    }

    .btn {
        padding: 14px 30px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
        box-shadow: 0 4px 15px rgba(66, 153, 225, 0.3);
    }

    .btn-secondary {
        background: #fff;
        color: #4a5568;
        border: 2px solid #e2e8f0;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    small.text-muted {
        color: #718096;
        font-size: 0.875rem;
        margin-top: 4px;
        display: block;
    }

    /* Add category filter styles */
    .category-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .category-filter {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        background: white;
        border: 2px solid #e2e8f0;
        color: #4a5568;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .category-filter.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* Items grid styles */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .item-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .item-card.selected {
        border-color: var(--primary-color);
        background: #f0f9ff;
    }

    .item-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .item-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .item-name {
        font-weight: 600;
        color: #2d3748;
    }

    .item-quantity {
        color: #718096;
        font-size: 0.875rem;
    }

    .selected-items {
        margin: 1rem 0;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
    }

    .selected-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        background: #f7fafc;
        border-radius: 0.25rem;
    }

    .remove-item {
        color: #e53e3e;
        cursor: pointer;
    }

    .item-image {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #f7fafc;
    }
</style>

<script>
    function toggleRequestForm() {
        const form = document.getElementById('requestForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    let searchTimeout;
    const searchInput = document.getElementById('itemSearch');
    const suggestionsContainer = document.getElementById('searchSuggestions');
    
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const query = e.target.value;
        
        if (query.length < 2) {
            suggestionsContainer.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            // Make AJAX call to get suggestions
            fetch(`/api/items/search?q=${query}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsContainer.innerHTML = data.map(item => `
                        <div class="suggestion-item" onclick="selectItem(${item.id}, '${item.name}')">
                            ${item.image ? `<img src="${item.image}" class="suggestion-thumb" alt="${item.name}">` : ''}
                            <div>
                                <div class="font-medium">${item.name}</div>
                                <div class="text-sm text-gray-600">Available: ${item.quantity}</div>
                            </div>
                        </div>
                    `).join('');
                    suggestionsContainer.style.display = 'block';
                });
        }, 300);
    });

    function selectItem(id, name, quantity) {
        document.getElementById('selectedItemId').value = id;
        document.getElementById('itemSearch').value = name;
        document.getElementById('selectedItemName').textContent = name;
        document.getElementById('availableQuantity').textContent = quantity;
        document.getElementById('selectedItemDetails').style.display = 'block';
        
        // Set max quantity
        document.getElementById('quantity').max = quantity;
        
        suggestionsContainer.style.display = 'none';
    }

    // Add quantity validation
    document.getElementById('quantity').addEventListener('input', function(e) {
        const max = parseInt(this.max);
        const value = parseInt(this.value);
        
        if (value > max) {
            this.value = max;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const categoryFilters = document.getElementById('categoryFilters');
        const itemsGrid = document.getElementById('itemsGrid');

        if (categoryFilters) {  // Check if element exists
            categoryFilters.addEventListener('click', function(e) {
                if (e.target.classList.contains('category-filter')) {
                    // Remove active class from all buttons
                    document.querySelectorAll('.category-filter').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    
                    // Add active class to clicked button
                    e.target.classList.add('active');
                    
                    // Get selected category
                    const category = e.target.dataset.category;
                    
                    // Filter items
                    if (itemsGrid) {
                        const items = itemsGrid.querySelectorAll('.item-card');
                        items.forEach(item => {
                            if (category === 'all' || item.dataset.category === category) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    }
                }
            });
        }
    });

    function selectItem(id, name, quantity) {
        // Remove previous selection
        document.querySelectorAll('.item-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selection to clicked item
        document.querySelector(`.item-card[data-id="${id}"]`).classList.add('selected');

        // Update form
        document.getElementById('selectedItemId').value = id;
        document.getElementById('selectedItemName').textContent = name;
        document.getElementById('availableQuantity').textContent = quantity;
        document.getElementById('selectedItemDetails').style.display = 'block';
        
        // Set max quantity
        document.getElementById('quantity').max = quantity;
    }

    let selectedItems = new Map();

    function toggleItemSelection(id, name, maxQuantity) {
        const itemCard = document.querySelector(`.item-card[data-id="${id}"]`);
        
        if (selectedItems.has(id)) {
            selectedItems.delete(id);
            itemCard.classList.remove('selected');
        } else {
            selectedItems.set(id, {
                name: name,
                maxQuantity: maxQuantity,
                quantity: 1
            });
            itemCard.classList.add('selected');
        }
        
        updateSelectedItemsList();
    }

    function updateSelectedItemsList() {
        const container = document.getElementById('selectedItems');
        const list = document.getElementById('selectedItemsList');
        
        if (selectedItems.size === 0) {
            container.style.display = 'none';
            return;
        }
        
        container.style.display = 'block';
        list.innerHTML = '';
        
        selectedItems.forEach((item, id) => {
            const div = document.createElement('div');
            div.className = 'selected-item';
            div.innerHTML = `
                <div>
                    <strong>${item.name}</strong>
                    <input type="hidden" name="items[${id}][id]" value="${id}">
                </div>
                <div class="d-flex align-items-center">
                    <input type="number" 
                        name="items[${id}][quantity]" 
                        value="${item.quantity}"
                        min="1" 
                        max="${item.maxQuantity}"
                        class="form-control form-control-sm mx-2" 
                        style="width: 80px"
                        onchange="updateItemQuantity(${id}, this.value)">
                    <span class="text-muted mr-3">/ ${item.maxQuantity}</span>
                    <i class="fas fa-times remove-item" onclick="toggleItemSelection(${id})"></i>
                </div>
            `;
            list.appendChild(div);
        });
    }

    function updateItemQuantity(id, quantity) {
        if (selectedItems.has(id)) {
            const item = selectedItems.get(id);
            item.quantity = Math.min(Math.max(1, parseInt(quantity)), item.maxQuantity);
            selectedItems.set(id, item);
            updateSelectedItemsList();
        }
    }
</script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">My Requests</h1>
        <button class="btn btn-primary" onclick="toggleRequestForm()">
            <i class="fas fa-plus"></i> New Request
        </button>
    </div>

    <div class="request-form mb-4" id="requestForm" style="display: none;">
        <div class="card-header">
            <h2>Request an Item</h2>
            <p class="text-secondary">Department: {{ auth()->user()->dept_name }}</p>
        </div>
        
        <div class="category-filters" id="categoryFilters">
            <button class="category-filter active" data-category="all">All Items</button>
            @foreach($categories as $key => $name)
                <button class="category-filter" data-category="{{ $key }}">
                    {{ $name }}
                </button>
            @endforeach
        </div>

        <div class="items-grid" id="itemsGrid">
            @foreach($items as $item)
                <div class="item-card" data-id="{{ $item->id }}" data-category="{{ $item->category }}"
                    onclick="toggleItemSelection({{ $item->id }}, '{{ $item->name }}', {{ $item->current_quantity }})">
                    <div class="item-image" style="background-image: url('{{ $item->image ?? 'https://via.placeholder.com/150?text='.$item->name }}')"></div>
                    <div class="item-details">
                        <div class="item-name">{{ $item->name }}</div>
                        <div class="item-quantity">Available: {{ $item->current_quantity }} {{ $item->unit_type }}</div>
                        <div class="item-category">{{ ucfirst(str_replace('_', ' ', $item->category)) }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('department.requests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="dept_name" value="{{ auth()->user()->dept_name }}">
            
            <div id="selectedItems" class="selected-items" style="display: none;">
                <h3 class="mb-3">Selected Items</h3>
                <div id="selectedItemsList"></div>
            </div>

            <div class="form-group">
                <label for="purpose">Purpose</label>
                <textarea 
                    class="form-control" 
                    id="purpose" 
                    name="purpose" 
                    rows="3" 
                    required
                    placeholder="Please explain why you need these items..."
                ></textarea>
                <small class="text-muted">Provide a clear explanation for your request</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="needed_by">Needed By Date</label>
                    <input type="date" 
                        class="form-control" 
                        id="needed_by" 
                        name="needed_by" 
                        required
                    >
                    <small class="text-muted">When do you need these items?</small>
                </div>
                <div class="form-group">
                    <label for="priority">Priority Level</label>
                    <select class="form-control" id="priority" name="priority" required>
                        <option value="">Select Priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
                <button type="button" class="btn btn-secondary" onclick="toggleRequestForm()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>

    <div class="requests-container">
        @if($requests->isEmpty())
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-secondary mb-3"></i>
                <p class="text-secondary">No requests found</p>
            </div>
        @else
            @foreach($requests as $request)
                <div class="request-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">{{ $request->item->name }}</h5>
                            <span class="status-badge status-{{ $request->status }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        <div class="actions">
                            @if($request->status === 'pending')
                                <form action="{{ route('department.requests.destroy', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this request?')">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('department.requests.show', $request) }}" class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>

                    <div class="request-details">
                        <div class="detail-item">
                            <span class="detail-label">Quantity</span>
                            <span class="detail-value">{{ $request->quantity_requested }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Requested Date</span>
                            <span class="detail-value">{{ $request->requested_date->format('M d, Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Delivery Date</span>
                            <span class="detail-value">{{ $request->delivery_date->format('M d, Y') }}</span>
                        </div>
                        @if($request->approved_date)
                            <div class="detail-item">
                                <span class="detail-label">Approved Date</span>
                                <span class="detail-value">{{ $request->approved_date->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-4">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection