@extends('layouts.admin')

@section('title', 'Add New Staff Member')

@section('styles')
    <x-form.styles />
    <style>
        .department-form {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
        }
        
        .department-form.show {
            max-height: 500px;
            opacity: 1;
            margin-top: 1rem;
        }
        
        .form-section {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .form-section-title {
            color: #374151;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Create New Staff Member</h4>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
            
            <form action="{{ route('admin.staff.store') }}" method="POST" id="staffForm">
                @csrf
                
                <div class="form-section">
                    <div class="form-section-title">Personal Information</div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.input 
                                label="Full Name"
                                name="name"
                                required="true"
                                placeholder="Enter full name" />
                        </div>
                        <div class="col-md-6">
                            <x-form.input 
                                type="email"
                                label="Email Address"
                                name="email"
                                required="true"
                                placeholder="Enter email address" />
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.input 
                                label="Employee ID"
                                name="employee_id"
                                required="true"
                                placeholder="Enter employee ID" />
                        </div>
                        <div class="col-md-6">
                            <x-form.input 
                                label="Phone Number"
                                name="phone"
                                placeholder="Enter phone number" />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Department & Role</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label>Department</label>
                                <button type="button" class="btn btn-sm btn-primary" 
                                        onclick="toggleDepartmentForm()">
                                    <i class="fas fa-plus"></i> Add New
                                </button>
                            </div>

                            <!-- Department Selection/Creation -->
                            <div id="departmentSelection">
                                <x-form.select
                                    name="department_id"
                                    required="true"
                                    placeholder="Select Department">
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">
                                            {{ $department->name }} ({{ $department->code }})
                                        </option>
                                    @endforeach
                                </x-form.select>
                            </div>

                            <div id="departmentForm" class="department-form">
                                <div class="card border">
                                    <div class="card-body">
                                        <form id="newDepartmentForm">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <input type="text" 
                                                           name="name" 
                                                           class="form-control" 
                                                           placeholder="Department Name" 
                                                           required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <input type="text" 
                                                               name="code" 
                                                               class="form-control" 
                                                               placeholder="Department Code" 
                                                               required>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <x-form.select
                                label="Role"
                                name="role"
                                required="true"
                                :options="$roles"
                                placeholder="Select Role" />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Security</div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.input 
                                type="password"
                                label="Password"
                                name="password"
                                required="true"
                                placeholder="Enter password" />
                        </div>
                        <div class="col-md-6">
                            <x-form.input 
                                type="password"
                                label="Confirm Password"
                                name="password_confirmation"
                                required="true"
                                placeholder="Confirm password" />
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function toggleDepartmentForm() {
        const form = document.getElementById('departmentForm');
        const selection = document.getElementById('departmentSelection');
        
        if (form && selection) {
            form.classList.toggle('show');
            selection.style.display = form.classList.contains('show') ? 'none' : 'block';
        }
    }

    // Make toggleDepartmentForm available globally
    window.toggleDepartmentForm = toggleDepartmentForm;

    const newDepartmentForm = document.getElementById('newDepartmentForm');
    if (newDepartmentForm) {
        newDepartmentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch("{{ route('admin.departments.store') }}", {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const selection = document.getElementById('departmentSelection');
                    const departmentInput = document.createElement('input');
                    departmentInput.type = 'hidden';
                    departmentInput.name = 'department_id';
                    departmentInput.value = data.department.id;
                    
                    selection.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Using new department: ${data.department.name} (${data.department.code})
                        </div>
                    `;
                    selection.appendChild(departmentInput);
                    
                    toggleDepartmentForm();
                    this.reset();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Department added successfully',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add department. Please try again.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    }

    const staffForm = document.getElementById('staffForm');
    if (staffForm) {
        staffForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset previous errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.remove();
            });
            
            let isValid = true;
            const errors = {};
            
            // Validate required fields
            this.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    errors[field.name] = `${field.getAttribute('label') || field.name} is required`;
                }
            });
            
            // Validate password match
            const password = this.querySelector('[name="password"]');
            const confirmation = this.querySelector('[name="password_confirmation"]');
            if (password && confirmation && password.value !== confirmation.value) {
                isValid = false;
                errors['password_confirmation'] = 'Passwords do not match';
            }
            
            if (!isValid) {
                // Display errors
                Object.keys(errors).forEach(fieldName => {
                    const field = this.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        field.classList.add('is-invalid');
                        
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = errors[fieldName];
                        field.parentNode.appendChild(feedback);
                    }
                });
                
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please check the form for errors',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                this.submit();
            }
        });
    }
});
</script>
@endsection

@endsection