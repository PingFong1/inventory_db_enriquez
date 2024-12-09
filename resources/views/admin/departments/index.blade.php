@extends('layouts.admin')

@section('title', 'Departments')

@section('styles')
<style>
    :root {
        --primary-color: #2196F3;
        --primary-dark: #1976D2;
        --success-color: #4CAF50;
        --danger-color: #F44336;
        --background-color: #F5F7FA;
    }

    .department-container {
        padding: 1.5rem;
        background: var(--background-color);
        min-height: 100vh;
    }

    .header-section {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        padding: 2rem;
        border-radius: 0.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table {
        background: white;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .table th {
        background: #E3F2FD;
        color: #1976D2;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-active {
        background-color: #E8F5E9;
        color: #2E7D32;
    }

    .status-inactive {
        background-color: #FFEBEE;
        color: #C62828;
    }

    .btn-edit {
        background-color: #03A9F4;
        color: white;
        border: none;
        padding: 0.5rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }

    .btn-edit:hover {
        background-color: #0288D1;
        transform: translateY(-1px);
    }

    .pagination-container {
        margin-top: 2rem;
        display: flex;
        justify-content-between;
        align-items: center;
    }

    .pagination-info {
        color: #64748B;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<div class="department-container">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-0">Departments Overview</h1>
                <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Staff Count</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td class="font-monospace">{{ $department->code }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{!! $department->status_badge !!}</td>
                                <td>{{ $department->staff_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.departments.edit', $department) }}" 
                                       class="btn btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $departments->firstItem() ?? 0 }} to {{ $departments->lastItem() ?? 0 }} 
            of {{ $departments->total() }} departments
        </div>
        <div class="d-flex gap-2">
            @if($departments->previousPageUrl())
                <a href="{{ $departments->previousPageUrl() }}" class="btn btn-outline-primary">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            @endif

            @if($departments->nextPageUrl())
                <a href="{{ $departments->nextPageUrl() }}" class="btn btn-outline-primary">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            @endif
        </div>
    </div>
</div>
@endsection