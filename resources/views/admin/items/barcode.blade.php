@extends('layouts.admin')

@section('title', 'Print Barcode')

@section('styles')
<style>
    .barcode-container {
        padding: 20px;
        text-align: center;
    }
    
    .barcode-card {
        display: inline-block;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin: 10px;
        background: white;
    }
    
    .item-details {
        margin-top: 10px;
        font-size: 12px;
    }
    
    .print-btn {
        margin: 20px 0;
    }
    
    @media print {
        .no-print {
            display: none;
        }
        .barcode-card {
            page-break-inside: avoid;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Barcode for {{ $item->name }}</h5>
            <button onclick="window.print()" class="btn btn-primary no-print">
                <i class="fas fa-print"></i> Print Barcode
            </button>
        </div>
        <div class="card-body">
            <div class="barcode-container">
                <div class="barcode-card">
                    <img src="{{ asset('storage/barcodes/'.$item->barcode.'.png') }}" alt="Barcode">
                    <div class="item-details">
                        <strong>{{ $item->name }}</strong><br>
                        SKU: {{ $item->sku }}<br>
                        Price: ₱{{ number_format($item->unit_price, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 