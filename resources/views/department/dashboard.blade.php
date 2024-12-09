@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* Updated color variables to match admin theme */
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1d4ed8;
        --secondary-color: #64748b;
        --success-color: #22c55e;
        --warning-color: #eab308;
        --danger-color: #ef4444;
        --background-color: #f1f5f9;
        --card-background: #ffffff;
    }

    /* Layout styles */
    .dashboard-container {
        padding: 1.5rem;
        background: var(--background-color);
        min-height: 100vh;
    }

    /* Header section */
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 2rem;
        border-radius: 0.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Stats grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--card-background);
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Button styles */
    .btn-primary {
        background: var(--primary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Welcome, {{ auth()->user()->name }}</h1>
        <p>View your request statistics below</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Pending Requests</h3>
            <div class="stat-value">{{ $pendingRequests }}</div>
        </div>
        <div class="stat-card">
            <h3>Approved Requests</h3>
            <div class="stat-value">{{ $approvedRequests }}</div>
        </div>
        <div class="stat-card">
            <h3>Total Requests</h3>
            <div class="stat-value">{{ $totalRequests }}</div>
        </div>
    </div>
</div>
@endsection