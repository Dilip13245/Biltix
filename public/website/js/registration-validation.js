/**
 * Single Source of Truth - Registration Validation System
 */

class RegistrationValidator {
    constructor() {
        this.disposableDomains = [
            'tempmail.org', '10minutemail.com', 'guerrillamail.com', 'mailinator.com', 
            'temp-mail.org', 'throwaway.email', 'yopmail.com', 'maildrop.cc',
            'sharklasers.com', 'example.com', 'example.org', 'test.com'
        ];
        
        this.genericCompanyNames = [
            'company', 'business', 'construction', 'contractor', 
            'consultant', 'corp', 'inc', 'ltd', 'llc', 'firm'
        ];
    }

    // Email Validation
    isValidProfessionalEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email) || email.length > 255) return false;
        
        const domain = email.split('@')[1]?.toLowerCase();
        const testDomains = ['example.com', 'example.org', 'test.com', 'localhost', 'domain.com'];
        return !testDomains.includes(domain);
    }

    isDisposableEmail(email) {
        const domain = email.split('@')[1]?.toLowerCase();
        return this.disposableDomains.includes(domain);
    }

    // Phone Validation
    isValidInternationalPhone(phone) {
        const cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
        const phoneRegex = /^[\+]?[1-9]\d{1,14}$/;
        return phoneRegex.test(cleanPhone);
    }

    // Password Validation
    isStrongPassword(password) {
        const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/;
        return strongRegex.test(password);
    }

    // Company Name Validation
    isGenericCompanyName(companyName) {
        const nameLower = companyName.toLowerCase().trim();
        return this.genericCompanyNames.some(generic => 
            nameLower === generic || nameLower.includes(generic)
        );
    }

    // Step 1: User Details Validation
    validateStep1(formData) {
        const errors = {};
        
        // Name validation
        const name = formData.name?.trim();
        if (!name) {
            errors.name = 'Full name is required';
        } else if (name.length < 2) {
            errors.name = 'Full name must be at least 2 characters';
        } else if (name.length > 100) {
            errors.name = 'Full name cannot exceed 100 characters';
        } else if (!/^[a-zA-Z\s\-\'\.]+$/.test(name)) {
            errors.name = 'Full name can only contain letters, spaces, hyphens, and apostrophes';
        }

        // Email validation
        const email = formData.email?.trim().toLowerCase();
        if (!email) {
            errors.email = 'Email address is required';
        } else if (!this.isValidProfessionalEmail(email)) {
            errors.email = 'Please enter a valid professional email address';
        } else if (this.isDisposableEmail(email)) {
            errors.email = 'Disposable email addresses are not allowed';
        }

        // Phone validation
        const phone = formData.phone?.trim();
        if (!phone) {
            errors.phone = 'Phone number is required';
        } else if (!this.isValidInternationalPhone(phone)) {
            errors.phone = 'Please enter a valid phone number (10-15 digits)';
        }

        // Password validation
        const password = formData.password;
        if (!password) {
            errors.password = 'Password is required';
        } else if (password.length < 8) {
            errors.password = 'Password must be at least 8 characters';
        } else if (!this.isStrongPassword(password)) {
            errors.password = 'Password must contain uppercase, lowercase, number, and special character';
        }

        // Confirm password validation
        const confirmPassword = formData.confirm_password;
        if (!confirmPassword) {
            errors.confirm_password = 'Please confirm your password';
        } else if (password !== confirmPassword) {
            errors.confirm_password = 'Passwords do not match';
        }

        return errors;
    }

    // Step 2: Company Details Validation
    validateStep2(formData) {
        const errors = {};

        // Company name validation
        const companyName = formData.company_name?.trim();
        if (!companyName) {
            errors.company_name = 'Company name is required';
        } else if (companyName.length < 2) {
            errors.company_name = 'Company name must be at least 2 characters';
        } else if (companyName.length > 200) {
            errors.company_name = 'Company name cannot exceed 200 characters';
        } else if (!/^[a-zA-Z0-9\s\-\&\.\,\(\)]+$/.test(companyName)) {
            errors.company_name = 'Company name contains invalid characters';
        } else if (this.isGenericCompanyName(companyName)) {
            errors.company_name = 'Please enter your actual company name';
        }

        // Employee count validation
        const employeeCount = formData.employee_count;
        if (!employeeCount) {
            errors.employee_count = 'Employee count is required';
        } else if (isNaN(employeeCount) || !Number.isInteger(Number(employeeCount))) {
            errors.employee_count = 'Employee count must be a whole number';
        } else if (parseInt(employeeCount) < 1) {
            errors.employee_count = 'Employee count must be at least 1';
        } else if (parseInt(employeeCount) > 50000) {
            errors.employee_count = 'Employee count cannot exceed 50,000';
        }

        // Designation validation
        const designation = formData.designation;
        if (!designation) {
            errors.designation = 'Please select your designation';
        } else if (!['consultant', 'contractor'].includes(designation)) {
            errors.designation = 'Invalid designation selected';
        }

        return errors;
    }

    // Step 3: Team Members Validation
    validateStep3(members, userPhone) {
        const errors = {};
        const phoneNumbers = new Set([userPhone?.trim()]);

        if (!members || !Array.isArray(members)) {
            return errors;
        }

        if (members.length > 50) {
            errors.general = 'Cannot add more than 50 team members';
            return errors;
        }

        members.forEach((member, index) => {
            const memberErrors = {};
            
            if (!member.name?.trim() && !member.phone?.trim()) {
                return;
            }

            // Name validation
            const name = member.name?.trim();
            if (!name) {
                memberErrors.name = 'Team member name is required';
            } else if (name.length < 2) {
                memberErrors.name = 'Team member name must be at least 2 characters';
            } else if (name.length > 100) {
                memberErrors.name = 'Team member name cannot exceed 100 characters';
            } else if (!/^[a-zA-Z\s\-\'\.]+$/.test(name)) {
                memberErrors.name = 'Team member name contains invalid characters';
            }

            // Phone validation
            const phone = member.phone?.trim();
            if (!phone) {
                memberErrors.phone = 'Team member phone is required';
            } else if (!this.isValidInternationalPhone(phone)) {
                memberErrors.phone = 'Please enter a valid phone number for team member';
            } else if (phoneNumbers.has(phone)) {
                memberErrors.phone = 'Duplicate phone numbers are not allowed';
            } else {
                phoneNumbers.add(phone);
            }

            if (Object.keys(memberErrors).length > 0) {
                errors[`member_${index}`] = memberErrors;
            }
        });

        return errors;
    }
}

// Export for global use
window.RegistrationValidator = RegistrationValidator;