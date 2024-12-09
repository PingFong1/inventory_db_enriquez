@extends('layouts.admin')

@section('title', 'Item Details')

@section('styles')
<style>
    .item-details-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .item-header {
        background: linear-gradient(135deg, rgba(30, 144, 255, 0.9), rgba(0, 92, 191, 0.9));
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(30, 144, 255, 0.3);
        backdrop-filter: blur(10px);
        color: white;
    }

    .item-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .info-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .info-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #1e90ff;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .info-label {
        color: #6c757d;
        font-weight: 500;
    }

    .info-value {
        font-weight: 600;
        color: #2d3436;
    }

    /* Stock Movement History */
    .stock-history {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        margin-top: 30px;
    }

    .history-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .history-table th {
        background: linear-gradient(135deg, #1e90ff, #005cbf);
        color: white;
        padding: 15px;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .history-table th:first-child { border-radius: 10px 0 0 10px; }
    .history-table th:last-child { border-radius: 0 10px 10px 0; }

    .history-table td {
        background: white;
        padding: 15px;
    }

    .history-table tr {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .history-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-badge i {
        font-size: 10px;
    }

    .status-in { background-color: rgba(46, 213, 115, 0.15); color: #2ed573; }
    .status-out { background-color: rgba(255, 71, 87, 0.15); color: #ff4757; }

    /* Quick Action Buttons */
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    /* Stock Adjustment Modals */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, #1e90ff, #005cbf);
        color: white;
        border-radius: 15px 15px 0 0;
        border: none;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 20px 0;
    }

    .quantity-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        background: #f8f9fa;
        color: #1e90ff;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .quantity-input {
        width: 100px;
        text-align: center;
        font-size: 1.2rem;
        padding: 8px;
        border: 1px solid #dee2e6;
        border-radius: 10px;
    }

    /* Barcode Section */
    .barcode-section {
        background: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        margin-top: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .barcode-section img {
        max-width: 300px;
        margin: 20px 0;
    }

    .print-options {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .print-option {
        padding: 10px 20px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .print-option.active {
        border-color: #1e90ff;
        color: #1e90ff;
        background: rgba(30, 144, 255, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .item-info-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }

    /* Print Styles */
    @media print {
        .no-print {
            display: none;
        }

        .barcode-section {
            page-break-inside: avoid;
        }
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }

    .modal.show {
        display: block;
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 1.75rem auto;
        max-width: 500px;
    }

    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        background: white;
        pointer-events: auto;
        outline: 0;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }

    /* Animation for modals */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: translate(0, -50px);
    }

    .modal.show .modal-dialog {
        transform: none;
    }

    /* Close button */
    .btn-close {
        background: transparent;
        border: none;
        font-size: 1.5rem;
        color: white;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Modern Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        z-index: 1050;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal.show {
        display: block;
        opacity: 1;
    }

    .modal-dialog {
        position: relative;
        width: 95%;
        max-width: 500px;
        margin: 1.75rem auto;
        transform: translateY(-50px);
        transition: transform 0.3s ease;
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
    }

    .modal-content {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, #1a73e8, #0d47a1);
        padding: 20px 30px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .modal-body {
        padding: 30px;
    }

    .modal-footer {
        padding: 20px 30px;
        background: #f8f9fa;
        border-top: 1px solid #eee;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #2d3436;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }

    /* Quantity Controls */
    .quantity-wrapper {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin: 15px 0;
    }

    .quantity-btn {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        border: none;
        background: white;
        color: #1a73e8;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .quantity-btn:hover {
        background: #1a73e8;
        color: white;
        transform: translateY(-2px);
    }

    .quantity-input {
        width: 120px;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 600;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 8px;
    }

    /* Value Display */
    .value-display {
        background: linear-gradient(135deg, #f6f8fa, #edf2f7);
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        margin: 15px 0;
    }

    .value-label {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 5px;
    }

    .value-amount {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a73e8;
    }

    /* Modal Buttons */
    .modal-btn {
        padding: 12px 25px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: #f1f3f5;
        color: #495057;
    }

    .btn-cancel:hover {
        background: #e9ecef;
    }

    .btn-confirm {
        background: #1a73e8;
        color: white;
    }

    .btn-confirm:hover {
        background: #0d47a1;
        transform: translateY(-2px);
    }

    /* Close Button */
    .btn-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .btn-close::before,
    .btn-close::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 2px;
        background: white;
        top: 50%;
        left: 50%;
    }

    .btn-close::before {
        transform: translate(-50%, -50%) rotate(45deg);
    }

    .btn-close::after {
        transform: translate(-50%, -50%) rotate(-45deg);
    }
</style>
@endsection

@section('content')
<div class="item-details-container">
    <div class="item-header">
        <div class="header-content">
            <h1>{{ $item->name }}</h1>
            <p class="text-white-50">SKU: {{ $item->sku }}</p>
        </div>
        <div class="quick-actions">
            <button type="button" class="btn-action" style="background: #2ecc71;" data-bs-toggle="modal" data-bs-target="#stockInModal">
                <i class="fas fa-plus-circle"></i> Stock In
            </button>
            <button type="button" class="btn-action" style="background: #f1c40f;" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                <i class="fas fa-minus-circle"></i> Stock Out
            </button>
            <button type="button" class="btn-action" style="background: #3498db;" data-bs-toggle="modal" data-bs-target="#barcodeModal">
                <i class="fas fa-barcode"></i> Print Barcode
            </button>
            <a href="{{ route('admin.items.edit', $item) }}" class="btn-action" style="background: #e67e22;">
                <i class="fas fa-edit"></i> Edit Item
            </a>
            <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action" style="background: #e74c3c;" 
                    onclick="return confirm('Are you sure you want to delete this item?')">
                    <i class="fas fa-trash"></i> Delete Item
                </button>
            </form>
        </div>
    </div>

    <div class="item-info-grid">
        <!-- Basic Information Card -->
        <div class="info-card">
            <div class="info-header">
                <i class="fas fa-info-circle text-primary"></i>
                <h3>Basic Information</h3>
            </div>
            <div class="info-content">
                @if($item->image_url)
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="item-image mb-3">
                @endif
                <div class="info-row">
                    <span class="info-label">Category</span>
                    <span class="info-value">{{ ucfirst($item->category) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Department</span>
                    <span class="info-value">{{ ucfirst($item->department) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Description</span>
                    <span class="info-value">{{ $item->description ?: 'No description available' }}</span>
                </div>
            </div>
        </div>

        <!-- Stock Information Card -->
        <div class="info-card">
            <div class="info-header">
                <i class="fas fa-box text-primary"></i>
                <h3>Stock Information</h3>
            </div>
            <div class="info-content">
                <div class="text-center mb-4">
                    @php
                        $statusClass = $item->current_quantity <= 0 ? 'status-out-of-stock' : 
                            ($item->current_quantity <= $item->minimum_quantity ? 'status-low-stock' : 'status-in-stock');
                        $statusText = $item->current_quantity <= 0 ? 'Out of Stock' : 
                            ($item->current_quantity <= $item->minimum_quantity ? 'Low Stock' : 'In Stock');
                    @endphp
                    <div class="status-badge {{ $statusClass }}">
                        <i class="fas fa-circle"></i>
                        {{ $statusText }}
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label">Current Stock</span>
                    <span class="info-value">{{ $item->current_quantity }} {{ $item->unit_type }}(s)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Minimum Level</span>
                    <span class="info-value">{{ $item->minimum_quantity }} {{ $item->unit_type }}(s)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Maximum Level</span>
                    <span class="info-value">{{ $item->maximum_quantity }} {{ $item->unit_type }}(s)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Unit Price</span>
                    <span class="info-value">₱{{ number_format($item->unit_price, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Value</span>
                    <span class="info-value">₱{{ number_format($item->current_quantity * $item->unit_price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movement History -->
    <div class="stock-history">
        <div class="info-header">
            <i class="fas fa-history text-primary"></i>
            <h3>Stock Movement History</h3>
        </div>
        <div class="table-responsive">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Performed By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockMovements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $movement->type }}">
                                    <i class="fas fa-{{ $movement->type === 'in' ? 'plus' : 'minus' }}-circle"></i>
                                    {{ ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td>{{ $movement->quantity }} {{ $item->unit_type }}(s)</td>
                            <td>{{ $movement->admin->name }}</td>
                            <td>{{ $movement->remarks ?: 'No remarks' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No stock movements recorded</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $stockMovements->links() }}
    </div>
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Stock In - {{ $item->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.items.stock-in', $item) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="quantity-wrapper">
                        <div class="form-group">
                            <label class="form-label">Quantity to Add</label>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn" onclick="decrementQuantity('stockInQuantity')">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="stockInQuantity" class="quantity-input" 
                                    required min="1" value="1" onchange="updateTotalValue('in')">
                                <button type="button" class="quantity-btn" onclick="incrementQuantity('stockInQuantity')">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">Maximum: {{ $item->maximum_quantity - $item->current_quantity }} units</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Price</label>
                        <input type="number" name="unit_price" class="form-control" 
                            value="{{ $item->unit_price }}" step="0.01" onchange="updateTotalValue('in')">
                    </div>

                    <div class="value-display">
                        <div class="value-label">Total Value</div>
                        <div class="value-amount" id="totalValueIn">₱0.00</div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" 
                            placeholder="Add any notes about this stock addition..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="modal-btn btn-confirm">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stock Out Modal -->
<div class="modal fade" id="stockOutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock Out - {{ $item->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.items.stock-out', $item) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Quantity to Remove</label>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn" onclick="decrementQuantity('stockOutQuantity')">-</button>
                            <input type="number" name="quantity" id="stockOutQuantity" class="form-control quantity-input" 
                                required min="1" max="{{ $item->current_quantity }}" value="1" onchange="updateTotalValue('out')">
                            <button type="button" class="quantity-btn" onclick="incrementQuantity('stockOutQuantity')">+</button>
                        </div>
                        <div class="mt-2 text-end">
                            <small class="text-muted">Available: {{ $item->current_quantity }} {{ $item->unit_type }}(s)</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Value</label>
                        <div class="form-control bg-light" id="totalValueOut">₱0.00</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" 
                            placeholder="Add any notes about this stock removal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-minus-circle"></i> Remove Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Barcode Modal -->
<div class="modal fade" id="barcodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Barcode - {{ $item->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="barcode-print-container">
                    <img src="{{ asset('storage/barcodes/'.$item->barcode.'.png') }}" alt="Barcode" class="img-fluid">
                    <div class="mt-3 text-center">
                        <strong>{{ $item->name }}</strong><br>
                        SKU: {{ $item->sku }}<br>
                        Price: ₱{{ number_format($item->unit_price, 2) }}
                    </div>
                </div>
                <div class="print-options">
                    <div class="print-option active" data-size="small">Small</div>
                    <div class="print-option" data-size="medium">Medium</div>
                    <div class="print-option" data-size="large">Large</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printBarcode()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function incrementQuantity(id) {
    const input = document.getElementById(id);
    const max = input.hasAttribute('max') ? parseInt(input.getAttribute('max')) : Infinity;
    const newValue = parseInt(input.value) + 1;
    if (newValue <= max) {
        input.value = newValue;
        updateTotalValue(id.includes('In') ? 'in' : 'out');
    }
}

function decrementQuantity(id) {
    const input = document.getElementById(id);
    const newValue = parseInt(input.value) - 1;
    if (newValue >= 1) {
        input.value = newValue;
        updateTotalValue(id.includes('In') ? 'in' : 'out');
    }
}

function updateTotalValue(type) {
    const quantity = document.getElementById(type === 'in' ? 'stockInQuantity' : 'stockOutQuantity').value;
    const unitPrice = type === 'in' ? 
        document.querySelector('input[name="unit_price"]').value : 
        {{ $item->unit_price }};
    const totalValue = quantity * unitPrice;
    document.getElementById(`totalValue${type.charAt(0).toUpperCase() + type.slice(1)}`).textContent = 
        '₱' + totalValue.toFixed(2);
}

function printBarcode() {
    const size = document.querySelector('.print-option.active').dataset.size;
    const printWindow = window.open('', '_blank');
    const barcodeImage = document.querySelector('.barcode-print-container').innerHTML;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Barcode - ${size}</title>
                <style>
                    body { margin: 0; padding: 20px; }
                    .barcode-container { text-align: center; }
                    .barcode-container img {
                        width: ${size === 'small' ? '200px' : size === 'medium' ? '300px' : '400px'};
                    }
                    @media print {
                        @page { margin: 0; }
                        body { margin: 1.6cm; }
                    }
                </style>
            </head>
            <body>
                <div class="barcode-container">
                    ${barcodeImage}
                </div>
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}

// Print options
document.querySelectorAll('.print-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.print-option').forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
    });
});

// Initialize tooltips and set initial values
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Set initial total values
    updateTotalValue('in');
    updateTotalValue('out');
});

// Modal handling functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Create and append backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop';
    document.body.appendChild(backdrop);
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('show');
    document.body.style.overflow = '';
    
    // Remove backdrop
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

// Initialize modal triggers
document.addEventListener('DOMContentLoaded', function() {
    // Handle modal triggers
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const targetModal = trigger.getAttribute('data-bs-target').replace('#', '');
            showModal(targetModal);
        });
    });

    // Handle close buttons
    document.querySelectorAll('.btn-close, [data-bs-dismiss="modal"]').forEach(closeBtn => {
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = closeBtn.closest('.modal');
            hideModal(modal.id);
        });
    });

    // Close modal when clicking outside
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                hideModal(modal.id);
            }
        });
    });

    // Handle ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const visibleModal = document.querySelector('.modal.show');
            if (visibleModal) {
                hideModal(visibleModal.id);
            }
        }
    });

    // Set initial total values
    updateTotalValue('in');
    updateTotalValue('out');
});
</script>
@endsection 