<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') ?? 'Billing System' }}</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Login page styling */
        .login-container {
            height: 100vh;
            width: 100vw;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('public/images/bgwatch.jpg') }}');
            background-size: cover;
            background-position: center;
            z-index: 0;
        }

        .login-form-overlay {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            z-index: 1;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .user-icon-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .user-icon-container i {
            font-size: 3rem;
            color: #304b8a;
            background: #f0f0f0;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 25px;
            padding: 12px 20px;
            border: 1px solid #ddd;
        }

        .form-options {
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .forgot-link {
            color: #304b8a;
            text-decoration: none;
        }

        .login-btn {
            width: 100%;
            border-radius: 25px;
            padding: 10px;
            background-color: #304b8a;
            border: none;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before, .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: #ddd;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .divider span {
            display: inline-block;
            padding: 0 10px;
            background-color: #fff;
            position: relative;
            z-index: 1;
            color: #777;
            font-size: 0.9rem;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #f5f5f5;
            color: #304b8a;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    
    @livewireStyles
</head>
<body>
    <div class="login-container">
        <!-- Full-screen background image -->
        <div class="background-image"></div>
        
        <!-- Centered login form overlay -->
        <div class="login-form-overlay">
            <!-- User icon -->
            <div class="user-icon-container">
                <i class="bi bi-person-circle"></i>
            </div>
            
            <div>
                <!-- Error messages -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Email field -->
                <div class="form-group">
                    <input type="email" class="form-control" wire:model="email" placeholder="Enter Email" required>
                </div>
                
                <!-- Password field -->
                <div class="form-group">
                    <input type="password" class="form-control" wire:model="password" placeholder="Enter Password" required>
                </div>
                
                <!-- Remember & Forgot options -->
                <div class="d-flex justify-content-between form-options">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password</a>
                </div>
                
                <!-- Login button -->
                <button type="button" wire:click="login" class="btn btn-primary login-btn">Login</button>
                
                <!-- Divider with text -->
                <div class="divider">
                    <span>Or Login With</span>
                </div>
                
                <!-- Social media login options -->
                <div class="social-login">
                    <a href="https://www.facebook.com/webxkey" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-google"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="https://www.linkedin.com/company/webxkey" class="social-icon"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    @livewireScripts
</body>
</html>
