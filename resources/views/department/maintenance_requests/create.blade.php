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
    .form-container {
        width: 60%;
        margin: 20px auto;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    label {
        font-weight: bold;
        color: #495057;
    }
    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 14px;
    }
    input[type="text"]:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
    button {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        align-self: flex-end;
    }
    button:hover {
        background-color: #0056b3;
    }
</style>

<h1>Create Maintenance Request</h1>
<div class="form-container">
    <form action="{{ route('department.maintenance_requests.store') }}" method="POST">
        @csrf
        <label for="requester_name">Name:</label>
        <input type="text" id="requester_name" name="requester_name" placeholder="Enter your name" required>

        <label for="contact_info">Contact Info:</label>
        <input type="text" id="contact_info" name="contact_info" placeholder="Enter your contact information" required>

        <label for="property_location">Location:</label>
        <input type="text" id="property_location" name="property_location" placeholder="Enter the property location" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" placeholder="Describe the issue" rows="4" required></textarea>

        <label for="priority">Priority:</label>
        <select id="priority" name="priority">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>

        <button type="submit">Submit</button>
    </form>
</div>
@endsection
