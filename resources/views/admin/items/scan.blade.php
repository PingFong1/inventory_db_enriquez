@extends('layouts.admin')

@section('title', 'Scan Item')

@section('styles')
<style>
    .scan-container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .scan-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .scan-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .scan-input {
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 1rem;
    }

    .scan-button {
        background: var(--primary-color);
        color: white;
        padding: 0.75rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .scan-button:hover {
        background: var(--primary-dark);
    }

    .scan-instructions {
        text-align: center;
        color: #64748b;
        margin-top: 2rem;
    }
</style>
@endsection

@section('content')
<div class="scan-container">
    <div class="scan-header">
        <h1>Scan Inventory Item</h1>
        <p>Scan or enter the barcode of an item to view its details</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.inventory.scan.process') }}" method="POST" class="scan-form">
        @csrf
        <input type="text" 
               name="barcode" 
               class="scan-input" 
               placeholder="Enter or scan barcode"
               autofocus
               required>
        
        <button type="submit" class="scan-button">
            <i class="fas fa-search"></i> Find Item
        </button>
    </form>

    <div class="scan-instructions">
        <p><i class="fas fa-info-circle"></i> Position the barcode scanner over the item's barcode or manually enter the barcode number</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus the input field
    document.querySelector('input[name="barcode"]').focus();

    // Handle barcode scanner input
    let barcodeInput = '';
    let lastKeyTime = Date.now();

    document.addEventListener('keypress', function(e) {
        const currentTime = Date.now();
        
        // If the delay between keystrokes is more than 100ms, assume it's manual typing
        // and reset the barcode input
        if (currentTime - lastKeyTime > 100) {
            barcodeInput = '';
        }

        // Update the last key time
        lastKeyTime = currentTime;

        // Add the character to the barcode input
        barcodeInput += e.key;

        // If we detect a return/enter key and we have barcode data
        if (e.key === 'Enter' && barcodeInput.length > 1) {
            e.preventDefault();
            
            // Remove the enter key from the barcode
            barcodeInput = barcodeInput.slice(0, -1);
            
            // Set the input value and submit the form
            document.querySelector('input[name="barcode"]').value = barcodeInput;
            document.querySelector('.scan-form').submit();
        }
    });
});
</script>
@endsection 