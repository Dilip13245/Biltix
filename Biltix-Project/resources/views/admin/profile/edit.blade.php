@extends('admin.layout.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Edit Profile Information</h6>
                <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Back to Profile
                </a>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Full Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $admin->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email', $admin->email) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Phone Number</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="{{ old('phone', $admin->phone ?? '') }}" 
                                   placeholder="+1 234 567 8900">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Role</label>
                            <input type="text" class="form-control" value="Super Admin" readonly>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="fw-bold mb-3">Change Password</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium">Current Password</label>
                            <input type="password" name="current_password" class="form-control" 
                                   placeholder="Enter current password">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium">New Password</label>
                            <input type="password" name="new_password" class="form-control" 
                                   placeholder="Enter new password">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" 
                                   placeholder="Confirm new password">
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection