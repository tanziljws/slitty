<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SMKN 4 BOGOR - Login Admin</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        
        /* Login Container - Split Layout */
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 1100px;
            min-height: 600px;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        /* Left Side - Blue Panel */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }
        
        .login-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -80px;
            left: -80px;
        }
        
        .left-content {
            position: relative;
            z-index: 2;
        }
        
        .logo-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 3rem;
        }
        
        .left-content h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .left-content p {
            font-size: 1.05rem;
            line-height: 1.8;
            opacity: 0.95;
            margin-bottom: 30px;
        }
        
        .learn-more-btn {
            display: inline-block;
            padding: 12px 30px;
            border: 2px solid white;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .learn-more-btn:hover {
            background: white;
            color: #3b82f6;
        }
        
        /* Right Side - Login Form */
        .login-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        .login-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: #3b82f6;
            margin: 15px auto 0;
            border-radius: 2px;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        
        .form-input::placeholder {
            color: #9ca3af;
        }
        
        /* Error Messages */
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .input-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }
        
        /* Checkbox */
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
        }
        
        .checkbox-group label {
            color: #64748b;
            font-size: 14px;
            cursor: pointer;
        }
        
        /* Login Button */
        .btn-login {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 16px 28px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
        
        /* Back to Home */
        .back-home {
            text-align: center;
            margin-top: 25px;
        }
        
        .back-home a {
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-home a:hover {
            color: #3b82f6;
        }
        
        /* Footer */
        .footer {
            position: static;
            text-align: center;
            padding: 15px;
            background: transparent;
            border-top: 1px solid rgba(59, 130, 246, 0.1);
            margin-top: auto;
            width: 100%;
        }
        
        .footer p {
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 968px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 500px;
            }
            
            .login-left {
                padding: 40px 30px;
            }
            
            .left-content h1 {
                font-size: 2rem;
            }
            
            .login-right {
                padding: 40px 30px;
            }
            
            .logo-icon {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }
        }
        @media (max-width: 640px) {
            .login-wrapper {
                max-width: 100%;
                min-height: auto;
                border-radius: 20px;
            }
            .login-left,
            .login-right {
                padding: 24px 20px;
            }
            .left-content h1 {
                font-size: 1.75rem;
            }
            .left-content p {
                font-size: 0.95rem;
            }
            .logo-icon {
                width: 64px;
                height: 64px;
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .learn-more-btn {
                width: 100%;
            }
            .login-title {
                font-size: 1.6rem;
            }
            .btn-login {
                padding: 14px 22px;
                font-size: 15px;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Login Wrapper - Split Layout -->
    <div class="login-wrapper">
        <!-- Left Side - Blue Panel -->
        <div class="login-left">
            <div class="left-content">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1>Welcome to<br>SMKN 4 BOGOR</h1>
                <p>Sistem Manajemen Galeri Digital untuk mengelola foto dan dokumentasi kegiatan sekolah dengan mudah dan efisien.</p>
                <a href="{{ route('user.dashboard') }}" class="learn-more-btn">Kembali ke Beranda</a>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-header">
                <h2 class="login-title">Sign In</h2>
            </div>

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email Field -->
                <div class="form-group">
                    <input type="text" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="form-input"
                           placeholder="Email atau Username..."
                           required autocomplete="username">
                    @error('email')
                        <p class="input-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <input type="password" 
                           name="password" 
                           class="form-input"
                           placeholder="Enter Password..."
                           required>
                    @error('password')
                        <p class="input-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="checkbox-group">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    Login
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} SMKN 4 BOGOR. Semua hak dilindungi.</p>
    </footer>
</body>
</html>
