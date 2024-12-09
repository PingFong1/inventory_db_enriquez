@extends('layouts.admin')

@section('title', 'Search Results')

@section('styles')
<style>
    .search-results {
        padding: 2rem;
    }

    .results-section {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .result-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s;
    }

    .result-item:last-child {
        border-bottom: none;
    }

    .result-item:hover {
        background-color: #f9fafb;
    }

    .result-title {
        color: #1e40af;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .result-meta {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .no-results {
        text-align: center;
        color: #6b7280;
        padding: 2rem;
    }
</style>
@endsection

@section('content')
<div class="search-results">
    <h2>Search Results for "{{ $query }}"</h2>

    @if($items->isEmpty() && $departments->isEmpty())
        <div class="no-results">
            <p>No results found for your search.</p>
        </div>
    @else
        @if($items->isNotEmpty())
            <div class="results-section">
                <h3>Items</h3>
                @foreach($items as $item)
                    <div class="result-item">
                        <a href="{{ route('admin.items.show', $item) }}" class="result-title">
                            {{ $item->name }}
                        </a>
                        <div class="result-meta">
                            <span>Category: {{ $item->category }}</span> •
                            <span>Department: {{ $item->department }}</span> •
                            <span>Stock: {{ $item->current_quantity }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($departments->isNotEmpty())
            <div class="results-section">
                <h3>Departments</h3>
                @foreach($departments as $department)
                    <div class="result-item">
                        <div class="result-title">{{ $department->name }}</div>
                        <div class="result-meta">
                            Items: {{ $department->items_count ?? 0 }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
@endsection 