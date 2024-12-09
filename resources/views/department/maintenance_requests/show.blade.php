@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }
    h1 {
        text-align: center;
        color: #343a40;
        margin-top: 20px;
    }
    .details-container {
        width: 60%;
        margin: 20px auto;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    p {
        font-size: 16px;
        line-height: 1.6;
        color: #495057;
        margin: 10px 0;
    }
    strong {
        font-weight: bold;
        color: #343a40;
    }
    a {
        text-decoration: none;
        color: #007bff;
    }
    a:hover {
        background-color: #0056b3;
    }
    
    
</style>

<h1>Maintenance Request Details</h1>
<div class="details-container">
    <p><strong>Name:</strong> {{ $maintenanceRequest->requester_name }}</p>
    <p><strong>Contact Info:</strong> {{ $maintenanceRequest->contact_info }}</p>
    <p><strong>Location:</strong> {{ $maintenanceRequest->property_location }}</p>
    <p><strong>Description:</strong> {{ $maintenanceRequest->description }}</p>
    <p><strong>Priority:</strong> {{ ucfirst($maintenanceRequest->priority) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($maintenanceRequest->status) }}</p>

    <a href="{{ route('department.maintenance_requests.index') }}">Back to List</a>
</div>
@endsection
