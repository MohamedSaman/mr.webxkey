<div>
    <div class="login-container">
        <!-- Full-screen background image -->
        <div class="background-image"></div>
        
        <!-- Centered login form overlay -->
        <div class="login-form-overlay">
            <!-- User icon -->
            <div class="user-icon-container">
                <i class="bi bi-person-circle"></i>
            </div>
            
            <form wire:submit.prevent="login">
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
                    <a href="#" class="forgot-link">Forgot Password</a>
                </div>
                
                <!-- Login button -->
                <button type="submit" class="btn btn-primary login-btn">Login</button>
                
                {{-- <!-- Divider with text -->
                <div class="divider">
                    <span>Or Login With</span>
                </div> --}}
                
                {{-- <!-- Social media login options -->
                <div class="social-login">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-google"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                </div> --}}
            </form>
        </div>
    </div>
</div>
