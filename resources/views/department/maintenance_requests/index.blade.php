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
    a {
        text-decoration: none;
        color: #007bff;
    }
    a:hover {
        color: #0056b3;
    }
    .btn {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        text-align: center;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .table-container {
        margin: 20px auto;
        width: 80%;
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #dee2e6;
    }
    th {
        background-color: #343a40;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    tr:hover {
        background-color: #e9ecef;
    }
    .actions {
        display: flex;
        gap: 5px;
    }
</style>

<h1>Maintenance Requests</h1>
<div class="table-container">
    <a class="btn btn-primary" href="{{ route('department.maintenance_requests.create') }}">Create New Request</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        @foreach ($requests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ $request->requester_name }}</td>
            <td>{{ $request->contact_info }}</td>
            <td>{{ $request->property_location }}</td>
            <td>{{ $request->status }}</td>
            <td class="actions">
                <a class="btn btn-primary" href="{{ route('department.maintenance_requests.show', $request) }}">View</a>
                <a class="btn btn-primary" href="{{ route('department.maintenance_requests.edit', $request) }}">Edit</a>
                <form action="{{ route('department.maintenance_requests.destroy', $request) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
