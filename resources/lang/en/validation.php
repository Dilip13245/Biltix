<?php

return [
    // Login validation
    'email_required' => 'Email address is required',
    'email_invalid' => 'Please enter a valid email address',
    'password_required' => 'Password is required',
    'password_min' => 'Password must be at least 8 characters',
    
    // Register validation
    'full_name_required' => 'Full name is required',
    'full_name_min' => 'Full name must be at least 2 characters',
    'full_name_max' => 'Full name cannot exceed 100 characters',
    'full_name_format' => 'Full name can only contain letters, spaces, hyphens, and apostrophes',
    
    'email_professional' => 'Please enter a valid professional email address',
    'email_max' => 'Email address is too long',
    'email_disposable' => 'Disposable email addresses are not allowed',
    'email_unique' => 'This email address is already registered',
    
    'phone_required' => 'Phone number is required',
    'phone_invalid' => 'Please enter a valid phone number (10-15 digits)',
    'phone_international' => 'Please use international format (+1234567890)',
    'phone_unique' => 'This phone number is already registered',
    
    'password_strong' => 'Password must contain uppercase, lowercase, number, and special character',
    'password_min_8' => 'Password must be at least 8 characters',
    'password_max' => 'Password cannot exceed 128 characters',
    'password_confirm_required' => 'Please confirm your password',
    'password_mismatch' => 'Passwords do not match',
    
    'company_name_required' => 'Company name is required',
    'company_name_min' => 'Company name must be at least 2 characters',
    'company_name_max' => 'Company name cannot exceed 200 characters',
    'company_name_format' => 'Company name contains invalid characters',
    'company_name_generic' => 'Please enter your actual company name',
    
    'employee_count_required' => 'Employee count is required',
    'employee_count_number' => 'Employee count must be a whole number',
    'employee_count_min' => 'Employee count must be at least 1',
    'employee_count_max' => 'Employee count cannot exceed 50,000',
    
    'designation_required' => 'Please select your designation',
    'designation_invalid' => 'Invalid designation selected',
    
    'members_max' => 'Cannot add more than 50 team members',
    'member_name_required' => 'Team member name is required',
    'member_name_min' => 'Team member name must be at least 2 characters',
    'member_name_max' => 'Team member name cannot exceed 100 characters',
    'member_name_format' => 'Team member name contains invalid characters',
    
    'member_phone_required' => 'Team member phone is required',
    'member_phone_invalid' => 'Please enter a valid phone number for team member',
    'member_phone_duplicate' => 'Duplicate phone numbers are not allowed',
    
    // Forgot password validation
    'mobile_required' => 'Mobile number is required',
    'mobile_invalid' => 'Please enter a valid mobile number',
    'otp_required' => 'OTP is required',
    'otp_invalid' => 'Please enter a valid 6-digit OTP',
    'new_password_required' => 'New password is required',
    'new_password_min' => 'New password must be at least 8 characters',
    'confirm_password_required' => 'Please confirm your new password',
    'new_password_mismatch' => 'New passwords do not match',
];