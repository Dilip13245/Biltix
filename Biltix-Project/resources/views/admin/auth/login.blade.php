<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Biltix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            --primary-solid: #ff6b35;
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow: 0 8px 32px 0 rgba(255, 107, 53, 0.37);
            --text-primary: #1a1d29;
            --text-secondary: #64748b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: grain 20s linear infinite;
        }
        
        @keyframes grain {
            0%, 100% { transform: translate(0, 0); }
            10% { transform: translate(-5%, -5%); }
            20% { transform: translate(-10%, 5%); }
            30% { transform: translate(5%, -10%); }
            40% { transform: translate(-5%, 15%); }
            50% { transform: translate(-10%, 5%); }
            60% { transform: translate(15%, 0%); }
            70% { transform: translate(0%, 10%); }
            80% { transform: translate(-15%, 0%); }
            90% { transform: translate(10%, 5%); }
        }
        
        /* Floating Elements */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 2rem;
        }
        
        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: var(--primary);
            color: white;
            padding: 3rem 2rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .logo {
            position: relative;
            z-index: 2;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .logo-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        
        .logo-subtitle {
            opacity: 0.9;
            font-weight: 400;
            margin: 0;
        }
        
        .login-body {
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-control {
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .form-control:focus {
            border-color: var(--primary-solid);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            background: white;
            transform: translateY(-2px);
        }
        
        .form-control::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }
        
        .btn-login {
            background: var(--primary);
            border: none;
            border-radius: 16px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        
        .demo-info {
            background: rgba(255, 107, 53, 0.1);
            border: 1px solid rgba(255, 107, 53, 0.2);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .demo-info .demo-title {
            font-weight: 600;
            color: var(--primary-solid);
            margin-bottom: 0.5rem;
        }
        
        .demo-info .demo-credentials {
            font-family: 'Courier New', monospace;
            background: rgba(255, 107, 53, 0.1);
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--text-primary);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-header {
                padding: 2rem 1.5rem 1.5rem;
            }
            
            .login-body {
                padding: 2rem 1.5rem;
            }
            
            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .logo-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-cube"></i>
                    </div>
                    <h1 class="logo-title">Biltix Admin</h1>
                    <p class="logo-subtitle">Construction Management System</p>
                </div>
            </div>
            
            <div class="login-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input type="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="Enter your email address" 
                               value="{{ old('email') }}"
                               required 
                               autocomplete="email">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Enter your password" 
                               required 
                               autocomplete="current-password">
                    </div>
                    
                    <button type="submit" class="btn btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        <span class="btn-text">Sign In to Admin Panel</span>
                    </button>
                </form>
                
                <div class="demo-info">
                    <div class="demo-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Demo Credentials
                    </div>
                    <div class="demo-credentials">
                        Email: admin@biltix.com<br>
                        Password: admin123
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Enhanced form interactions
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            
            btn.disabled = true;
            btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
        });
        
        // Auto-fill demo credentials on click
        document.querySelector('.demo-credentials').addEventListener('click', function() {
            document.querySelector('input[name="email"]').value = 'admin@biltix.com';
            document.querySelector('input[name="password"]').value = 'admin123';
            
            // Add visual feedback
            this.style.background = 'rgba(255, 107, 53, 0.2)';
            setTimeout(() => {
                this.style.background = 'rgba(255, 107, 53, 0.1)';
            }, 200);
        });
        
        // Enhanced input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>