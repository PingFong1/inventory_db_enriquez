@extends('layouts.admin')

@section('title', 'Add New Item')

@section('styles')
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .card {
        background: linear-gradient(145deg, #ffffff, #f5f7fa);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 40px;
    }

    .card-header {
        margin-bottom: 35px;
        border-bottom: 2px solid #eef2f7;
        padding-bottom: 20px;
    }

    .card-header h2 {
        color: #2d3748;
        font-size: 2rem;
        font-weight: 600;
        margin: 0;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 25px;
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

    select.form-control {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%234a5568' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 16px;
        padding-right: 45px;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
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

    .btn-primary:hover {
        background: linear-gradient(135deg, #3182ce, #2c5282);
    }

    .btn-secondary:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
    }

    .form-actions {
        display: flex;
        gap: 20px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #eef2f7;
    }

    /* Add subtle animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card {
        animation: fadeIn 0.5s ease-out;
    }

    /* Add to your existing styles section */
    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 30px;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 1000;
        animation: slideIn 0.5s ease-out;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .alert-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .alert-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .alert-fadeout {
        animation: fadeOut 0.5s ease-out forwards;
    }

    /* Add to your existing styles */
    .form-control.error {
        border-color: #ef4444;
        background-color: #fef2f2;
    }

    .form-control.error:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    /* Add to your existing styles */
    .quantity-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .quantity-high {
        background-color: #22c55e;
    }

    .quantity-medium {
        background-color: #eab308;
    }

    .quantity-low {
        background-color: #ef4444;
    }
</style>
@endsection

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error" id="error-alert">
            <i class="fas fa-exclamation-circle"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2>Add New Item</h2>
        </div>
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="cleaning">Cleaning Supplies</option>
                    <option value="maintenance">Maintenance Tools</option>
                    <option value="safety">Safety Equipment</option>
                    <option value="furniture">Furniture</option>
                    <option value="electronics">Electronics</option>
                    <option value="office">Office Supplies</option>
                    <option value="chemicals">Cleaning Chemicals</option>
                    <option value="paper">Paper Products</option>
                </select>
            </div>

            <div class="form-group">
                <label for="department">Department/Location</label>
                <select class="form-control" id="department" name="department" required>
                    <option value="">Select Location</option>
                    <option value="classroom">Classrooms</option>
                    <option value="laboratory">Laboratories</option>
                    <option value="office">Administrative Offices</option>
                    <option value="library">Library</option>
                    <option value="cafeteria">Cafeteria</option>
                    <option value="gymnasium">Gymnasium</option>
                    <option value="maintenance">Maintenance Area</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="current_quantity">Current Quantity</label>
                    <input type="number" class="form-control" id="current_quantity" name="current_quantity" 
                        min="0" value="{{ old('current_quantity') }}" required>
                    <small class="text-muted">Current stock level</small>
                </div>

                <div class="form-group">
                    <label for="minimum_quantity">Minimum Quantity</label>
                    <input type="number" class="form-control" id="minimum_quantity" name="minimum_quantity" 
                        min="0" value="{{ old('minimum_quantity') }}" required>
                    <small class="text-muted">Alert threshold for low stock</small>
                </div>

                <div class="form-group">
                    <label for="maximum_quantity">Maximum Quantity</label>
                    <input type="number" class="form-control" id="maximum_quantity" name="maximum_quantity" 
                        min="0" value="{{ old('maximum_quantity') }}" required>
                    <small class="text-muted">Maximum storage capacity</small>
                </div>
            </div>

            <div class="form-group">
                <label for="usage_frequency">Usage Frequency</label>
                <select class="form-control" id="usage_frequency" name="usage_frequency" required>
                    <option value="">Select Frequency</option>
                    <option value="daily">Daily Use</option>
                    <option value="weekly">Weekly Use</option>
                    <option value="monthly">Monthly Use</option>
                    <option value="semester">Per Semester</option>
                </select>
            </div>

            <div class="form-group">
                <label for="budget_category">Budget Category</label>
                <select class="form-control" id="budget_category" name="budget_category" required>
                    <option value="">Select Budget Category</option>
                    <option value="regular">Regular Budget</option>
                    <option value="emergency">Emergency Fund</option>
                    <option value="special">Special Project</option>
                    <option value="department">Department Budget</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="unit_price">Unit Price</label>
                    <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price') }}" required>
                </div>

                <div class="form-group">
                    <label for="unit_type">Unit Type</label>
                    <select class="form-control" id="unit_type" name="unit_type" required>
                        <option value="piece">Piece</option>
                        <option value="box">Box</option>
                        <option value="kg">Kilogram</option>
                        <!-- Add more unit types as needed -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode') }}">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="available">Available</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Item</button>
                <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle success alert
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');

    function hideAlert(alertElement) {
        if (alertElement) {
            setTimeout(() => {
                alertElement.classList.add('alert-fadeout');
                setTimeout(() => {
                    alertElement.remove();
                }, 500);
            }, 3000);
        }
    }

    hideAlert(successAlert);
    hideAlert(errorAlert);

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
            } else {
                field.classList.remove('error');
            }
        });

        if (!isValid) {
            e.preventDefault();
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-error';
            errorAlert.innerHTML = `
                <i class="fas fa-exclamation-circle"></i>
                Please fill in all required fields.
            `;
            document.body.appendChild(errorAlert);
            hideAlert(errorAlert);
        }
    });

    // Quantity validation
    const currentQuantityInput = document.getElementById('current_quantity');
    const minimumQuantityInput = document.getElementById('minimum_quantity');
    const maximumQuantityInput = document.getElementById('maximum_quantity');

    function validateQuantities() {
        const current = parseInt(currentQuantityInput.value) || 0;
        const minimum = parseInt(minimumQuantityInput.value) || 0;
        const maximum = parseInt(maximumQuantityInput.value) || 0;

        if (maximum < minimum) {
            maximumQuantityInput.setCustomValidity('Maximum quantity must be greater than or equal to minimum quantity');
        } else {
            maximumQuantityInput.setCustomValidity('');
        }

        if (current > maximum) {
            currentQuantityInput.setCustomValidity('Current quantity cannot exceed maximum quantity');
        } else {
            currentQuantityInput.setCustomValidity('');
        }
    }

    currentQuantityInput.addEventListener('input', validateQuantities);
    minimumQuantityInput.addEventListener('input', validateQuantities);
    maximumQuantityInput.addEventListener('input', validateQuantities);
});
</script>
@endsection