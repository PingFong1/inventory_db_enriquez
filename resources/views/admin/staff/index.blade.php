@extends('layouts.admin')

@section('title', 'Staff Management')

@section('styles')
<style>
    .staff-container {
        padding: 1.5rem;
        background: var(--background-color);
        min-height: 100vh;
    }

    .header-section {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 2rem;
        border-radius: 0.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
    }

    .status-active {
        background-color: var(--success-color);
        color: white;
    }

    .status-inactive {
        background-color: var(--danger-color);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="staff-container">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-0">Staff Management</h1>
                <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <a href="{{ route('admin.staff.create') }}" class="btn btn-light">
                <i class="fas fa-plus"></i> Add New Staff
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $member)
                            <tr>
                                <td>{{ $member->employee_id }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->department->name }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $member->role)) }}</td>
                                <td>{!! $member->status_badge !!}</td>
                                <td>
                                    <a href="{{ route('admin.staff.edit', $member) }}" 
                                       class="btn btn-sm btn-info"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.staff.destroy', $member) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No staff members found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $staff->links() }}
        </div>
    </div>
</div>
@endsection