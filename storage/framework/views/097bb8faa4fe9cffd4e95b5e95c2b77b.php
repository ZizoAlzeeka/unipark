<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniPark — Smart Campus Parking · University of Ha'il</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/unipark.css')); ?>">
</head>
<body class="landing-page">

<!-- ══════════════════ HERO ══════════════════ -->
<section class="landing-hero">
    <div class="landing-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
    </div>

    <nav class="landing-nav">
        <div class="landing-logo">
            <div class="landing-logo-icon">
                <i class="fas fa-parking"></i>
            </div>
            <div>
                <div class="landing-logo-text">UniPark</div>
                <div class="landing-logo-sub">University of Ha'il</div>
            </div>
        </div>
        <div class="landing-nav-actions">
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-nav-register">
                    <i class="fas fa-tachometer-alt"></i>&nbsp; Dashboard
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn-nav-login">Sign In</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-nav-register">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="landing-hero-content">
        <div class="landing-hero-text">
            <div class="landing-badge">
                <span class="badge-dot"></span>
                Smart Parking Technology &mdash; University of Ha'il
            </div>
            <h1 class="landing-h1">
                Park Smarter.<br><span>Drive Better.</span>
            </h1>
            <p class="landing-desc">
                UniPark is the intelligent campus parking management system that helps
                students and staff find, reserve, and manage parking spots — all from
                one beautiful platform.
            </p>
            <div class="landing-cta">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn-cta-primary">
                        <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn-cta-primary">
                        <i class="fas fa-rocket"></i> Get Started Free
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="btn-cta-secondary">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </a>
                <?php endif; ?>
            </div>
            <div class="landing-stats">
                <div class="landing-stat">
                    <div class="landing-stat-value">1,200+</div>
                    <div class="landing-stat-label">Parking Spots</div>
                </div>
                <div class="landing-stat" style="border-left:1px solid rgba(255,255,255,0.18);border-right:1px solid rgba(255,255,255,0.18);padding:0 48px;">
                    <div class="landing-stat-value">5,000+</div>
                    <div class="landing-stat-label">Registered Users</div>
                </div>
                <div class="landing-stat">
                    <div class="landing-stat-value">98%</div>
                    <div class="landing-stat-label">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════ FEATURES ══════════════════ -->
<section class="features-section">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="text-align:center;">
            <span class="section-tag">Why UniPark?</span>
            <h2 class="section-title">Everything you need, in one place</h2>
            <p class="section-subtitle" style="margin:12px auto 0;">From real-time availability to smart reservations — the complete parking solution for your campus.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card animate-fade-up stagger-1">
                <div class="feature-icon" style="background:rgba(79,70,229,0.1);color:var(--primary);">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3 class="feature-title">Live Parking Map</h3>
                <p class="feature-desc">See available, reserved, and occupied spots in real-time on an interactive Google Maps interface. Navigate directly to your spot.</p>
            </div>
            <div class="feature-card animate-fade-up stagger-2">
                <div class="feature-icon" style="background:rgba(14,165,233,0.1);color:var(--secondary);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="feature-title">Smart Reservations</h3>
                <p class="feature-desc">Reserve your spot in advance with just a few taps. Set start and end times, get reminders, and never circle the parking lot again.</p>
            </div>
            <div class="feature-card animate-fade-up stagger-3">
                <div class="feature-icon" style="background:rgba(16,185,129,0.1);color:var(--success);">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="feature-title">Smart Notifications</h3>
                <p class="feature-desc">Receive instant alerts when your reservation is confirmed, when your time is almost up, or when a preferred spot opens up.</p>
            </div>
            <div class="feature-card animate-fade-up stagger-4">
                <div class="feature-icon" style="background:rgba(245,158,11,0.1);color:var(--warning);">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title">Role-Based Access</h3>
                <p class="feature-desc">Separate dashboards and permissions for Students, Staff, and Administrators. Everyone gets exactly what they need.</p>
            </div>
            <div class="feature-card animate-fade-up stagger-5">
                <div class="feature-icon" style="background:rgba(239,68,68,0.1);color:var(--danger);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="feature-title">Violation Tracking</h3>
                <p class="feature-desc">Admins can log and manage parking violations, track repeat offenders, and keep campus parking fair for everyone.</p>
            </div>
            <div class="feature-card animate-fade-up stagger-6">
                <div class="feature-icon" style="background:rgba(6,182,212,0.1);color:var(--info);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Analytics & Reports</h3>
                <p class="feature-desc">Deep usage insights, peak hour analysis, zone utilization reports, and export capabilities for data-driven decisions.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════ HOW IT WORKS ══════════════════ -->
<section class="howitworks-section">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="text-align:center;">
            <span class="section-tag">Simple Process</span>
            <h2 class="section-title">Get parked in 4 easy steps</h2>
            <p class="section-subtitle" style="margin:12px auto 0;">UniPark makes campus parking effortless from start to finish.</p>
        </div>
        <div class="steps">
            <div class="step animate-fade-up stagger-1">
                <div class="step-number">1</div>
                <h4 class="step-title">Create Account</h4>
                <p class="step-desc">Register with your University of Ha'il email. Verification is instant and secure.</p>
            </div>
            <div class="step animate-fade-up stagger-2">
                <div class="step-number">2</div>
                <h4 class="step-title">View Live Map</h4>
                <p class="step-desc">See all zones and available spots in real-time on the campus map.</p>
            </div>
            <div class="step animate-fade-up stagger-3">
                <div class="step-number">3</div>
                <h4 class="step-title">Reserve Your Spot</h4>
                <p class="step-desc">Pick your zone, select a spot, and confirm your reservation instantly.</p>
            </div>
            <div class="step animate-fade-up stagger-4">
                <div class="step-number">4</div>
                <h4 class="step-title">Park & Go</h4>
                <p class="step-desc">Show your QR code on entry and park with zero hassle every time.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════ ZONES ══════════════════ -->
<section style="padding:90px 60px;background:#fff;">
    <div style="max-width:1200px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:50px;">
            <span class="section-tag">Campus Zones</span>
            <h2 class="section-title">Dedicated zones for every need</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:20px;">
            <?php
            $zones = [
                ['name'=>'Zone A','sub'=>'Main Building','icon'=>'fa-building','color'=>'var(--grad-primary)'],
                ['name'=>'Zone B','sub'=>'Faculty Housing','icon'=>'fa-home','color'=>'var(--grad-secondary)'],
                ['name'=>'Zone C','sub'=>'Student Services','icon'=>'fa-user-graduate','color'=>'var(--grad-success)'],
                ['name'=>'Zone D','sub'=>'Medical Center','icon'=>'fa-hospital','color'=>'var(--grad-danger)'],
                ['name'=>'Zone E','sub'=>'Sports Complex','icon'=>'fa-running','color'=>'var(--grad-warning)'],
                ['name'=>'Zone F','sub'=>'Engineering College','icon'=>'fa-cogs','color'=>'var(--grad-info)'],
            ];
            ?>
            <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="zone-card">
                <div class="zone-card-header" style="background:<?php echo e($zone['color']); ?>;padding:20px 18px;">
                    <div style="font-size:26px;color:rgba(255,255,255,0.9);margin-bottom:6px;"><i class="fas <?php echo e($zone['icon']); ?>"></i></div>
                    <div style="font-size:17px;font-weight:800;color:#fff;"><?php echo e($zone['name']); ?></div>
                    <div style="font-size:12px;color:rgba(255,255,255,0.7);"><?php echo e($zone['sub']); ?></div>
                </div>
                <div class="zone-card-body">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:13px;color:var(--text-muted);font-weight:600;">Status</span>
                        <span class="badge badge-success">Active</span>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<!-- ══════════════════ CTA ══════════════════ -->
<section class="cta-section">
    <div class="landing-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-3"></div>
    </div>
    <div style="position:relative;z-index:2;max-width:700px;margin:0 auto;">
        <h2 class="cta-title">Ready to park smarter?</h2>
        <p class="cta-desc">Join thousands of students and staff at University of Ha'il who have already made the switch to smart campus parking.</p>
        <div class="landing-cta">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('register')); ?>" class="btn-cta-primary">
                    <i class="fas fa-rocket"></i> Create Free Account
                </a>
                <a href="<?php echo e(route('login')); ?>" class="btn-cta-secondary">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-cta-primary">
                    <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ══════════════════ FOOTER ══════════════════ -->
<footer class="landing-footer">
    <div class="landing-logo">
        <div class="landing-logo-icon" style="background:var(--grad-primary);width:38px;height:38px;font-size:16px;">
            <i class="fas fa-parking"></i>
        </div>
        <div>
            <div style="font-size:15px;font-weight:800;color:var(--text-primary);">UniPark</div>
            <div style="font-size:11px;color:var(--text-muted);">University of Ha'il</div>
        </div>
    </div>
    <div class="footer-links">
        <?php if(auth()->guard()->guest()): ?>
            <a href="<?php echo e(route('login')); ?>">Sign In</a>
            <a href="<?php echo e(route('register')); ?>">Register</a>
        <?php endif; ?>
        <a href="#">Help Center</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
    </div>
    <div class="footer-copy">&copy; <?php echo e(date('Y')); ?> University of Ha'il. All rights reserved.</div>
</footer>

</body>
</html>
<?php /**PATH E:\xamp\htdocs\unipark\resources\views/landing.blade.php ENDPATH**/ ?>