<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniPark — Smart Campus Parking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Base styles -->
    <link rel="stylesheet" href="{{ asset('css/unipark.css') }}">
    <!-- New Creative UI Styles -->
    <link rel="stylesheet" href="{{ asset('css/creative-design.css') }}">
</head>
<body class="creative-layout">

    <div class="animated-bg"></div>
    
    <!-- Background Orbs for extra flair -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Glass Navigation -->
    <nav class="glass-nav">
        <div class="nav-container">
            <a href="#" class="logo-creative">
                <div class="logo-icon-creative">
                    <i class="fas fa-parking"></i>
                </div>
                UniPark
            </a>
            
            <div style="display:flex; gap:16px; align-items:center;">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-creative btn-creative-glass">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-creative btn-creative-glass" style="font-weight:600;">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="btn-creative btn-creative-primary">
                        Get Started <i class="fas fa-arrow-right"></i>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="landing-hero">
        <div class="hero-content">
            <div class="glass-panel" style="display:inline-block; padding:8px 24px; border-radius:50px; margin-bottom:30px; font-weight:600; font-size:14px; border-color:rgba(255,255,255,0.2);">
                ✨ <span class="text-grad-1">Smart Parking Technology</span> — University of Ha'il
            </div>
            
            <h1 class="hero-title floating-element">
                Park Smarter. <br>
                <span class="text-grad-2">Drive Better.</span>
            </h1>
            
            <p class="hero-subtitle floating-element-delay">
                Experience the next generation of campus parking. Real-time availability, instant reservations, and intelligent routing—all wrapped in a beautifully seamless platform.
            </p>
            
            <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap;">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-creative btn-creative-primary" style="padding:16px 40px; font-size:18px;">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-creative btn-creative-primary" style="padding:16px 40px; font-size:18px;">
                        <i class="fas fa-rocket"></i> Start Free Trial
                    </a>
                    <a href="{{ route('login') }}" class="btn-creative btn-creative-glass" style="padding:16px 40px; font-size:18px;">
                        Sign In Now
                    </a>
                @endauth
            </div>
            
            <!-- Quick Stats -->
            <div class="glass-panel" style="margin-top:60px; display:flex; justify-content:space-around; padding:30px; gap:20px; flex-wrap:wrap;">
                <div>
                    <div style="font-size:32px; font-weight:900; line-height:1;" class="text-grad-1">1,200+</div>
                    <div style="font-size:14px; color:rgba(255,255,255,0.6); margin-top:8px;">Parking Spots</div>
                </div>
                <div style="width:1px; background:rgba(255,255,255,0.1);"></div>
                <div>
                    <div style="font-size:32px; font-weight:900; line-height:1;" class="text-grad-2">5,000+</div>
                    <div style="font-size:14px; color:rgba(255,255,255,0.6); margin-top:8px;">Active Users</div>
                </div>
                <div style="width:1px; background:rgba(255,255,255,0.1);"></div>
                <div>
                    <div style="font-size:32px; font-weight:900; line-height:1;" class="text-grad-1">99%</div>
                    <div style="font-size:14px; color:rgba(255,255,255,0.6); margin-top:8px;">Uptime</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="creative-features">
        <div style="text-align:center; margin-bottom:60px;">
            <h2 style="font-size:3rem; font-weight:800; margin-bottom:16px;">
                Everything you need, <span class="text-grad-1">perfectly designed.</span>
            </h2>
            <p style="color:rgba(255,255,255,0.7); font-size:1.2rem; max-width:600px; margin:0 auto;">
                We built UniPark to make your daily commute completely effortless.
            </p>
        </div>
        
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:30px;">
            
            <div class="glass-panel feature-card-creative">
                <div class="feature-icon-wrapper text-grad-2">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3 class="feature-title-creative">Live Interactive Map</h3>
                <p class="feature-desc-creative">See exactly which spots are available in real-time across the entire campus. Never waste time circling again.</p>
            </div>
            
            <div class="glass-panel feature-card-creative">
                <div class="feature-icon-wrapper text-grad-1">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="feature-title-creative">Smart Reservations</h3>
                <p class="feature-desc-creative">Secure your parking spot hours before you arrive. Guarantee your space with our intelligent booking engine.</p>
            </div>
            
            <div class="glass-panel feature-card-creative">
                <div class="feature-icon-wrapper text-grad-2">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="feature-title-creative">Instant Alerts</h3>
                <p class="feature-desc-creative">Get push notifications when your reserved time is ending, or when prime spots open up near your classes.</p>
            </div>

            <div class="glass-panel feature-card-creative">
                <div class="feature-icon-wrapper text-grad-1">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3 class="feature-title-creative">Analytics Dashboard</h3>
                <p class="feature-desc-creative">Track your parking history, analyze your usage patterns, and manage your account through a beautiful interface.</p>
            </div>
            
            <div class="glass-panel feature-card-creative">
                <div class="feature-icon-wrapper text-grad-2">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title-creative">Secure Access</h3>
                <p class="feature-desc-creative">Advanced role-based security ensures your data is protected. Seamless integration with university credentials.</p>
            </div>
            
            <div class="glass-panel feature-card-creative">
                <div class="feature-icon-wrapper text-grad-1">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="feature-title-creative">Lightning Fast</h3>
                <p class="feature-desc-creative">Built on a highly optimized architecture, UniPark delivers instant responses even during peak campus hours.</p>
            </div>

        </div>
    </section>
    
    <!-- Footer CTA -->
    <section style="padding:100px 5%; text-align:center; position:relative;">
        <div class="orb orb-3"></div>
        <div class="glass-panel" style="max-width:800px; margin:0 auto; padding:60px 40px; position:relative; z-index:10;">
            <h2 style="font-size:2.5rem; font-weight:800; margin-bottom:20px;">Ready to transform your commute?</h2>
            <p style="color:rgba(255,255,255,0.7); font-size:1.1rem; margin-bottom:40px;">Join thousands of students and staff today.</p>
            <a href="{{ route('register') }}" class="btn-creative btn-creative-primary" style="padding:18px 50px; font-size:18px;">
                Create Your Account
            </a>
        </div>
    </section>

    <footer style="text-align:center; padding:40px; color:rgba(255,255,255,0.4); font-size:14px; border-top:1px solid rgba(255,255,255,0.05); position:relative; z-index:10;">
        &copy; {{ date('Y') }} University of Ha'il. All rights reserved.
    </footer>

</body>
</html>
