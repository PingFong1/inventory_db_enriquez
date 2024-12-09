@extends('layouts.admin')

@section('title', 'Generate Report')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Inventory Report</h2>
        </div>
        <div class="card-body">
            <h3>Inventory Summary</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Department</th>
                            <th>Current Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->department }}</td>
                            <td>{{ $item->current_quantity }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 