@extends('layouts.admin')

@section('title', 'Admin Settings')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>System Settings</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')
                
                <h3>General Settings</h3>
                <div class="form-group">
                    <label>System Name</label>
                    <input type="text" class="form-control" name="system_name" value="{{ config('app.name') }}">
                </div>

                <h3 class="mt-4">Notification Settings</h3>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="emailNotifications" name="email_notifications">
                    <label class="form-check-label" for="emailNotifications">Enable Email Notifications</label>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection 