<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - SMKN 4 BOGOR</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
            scroll-behavior: smooth;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 40px;
        }
        
        @media (min-width: 1400px) {
            .container {
                padding: 0 60px;
            }
        }
        
        /* Header Section - Modern Navbar Style */
        .header {
            background: white;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }
        
        .branding {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .brand-icon {
            width: 60px;
            height: 60px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .brand-icon svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 2px 4px rgba(30, 58, 138, 0.2));
        }
        
        /* Added img styling to match other pages */
        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .brand-text h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            line-height: 1.2;
        }
        
        .brand-text p {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            margin: 0;
            line-height: 1;
        }
        
        /* Navigation Menu */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-links li a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .nav-links li a:hover {
            color: #3b82f6;
            background: #f1f5f9;
        }
        
        .nav-links li a.active {
            color: white;
            background: #3b82f6;
        }
        
        .login-btn {
            background: #3b82f6;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .login-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* Main Content */
        .main-content {
            padding: 60px 0;
        }
        
        .hero-section {
            text-align: center;
            margin-bottom: 60px;
            padding: 40px 20px;
            background: transparent;
            border-radius: 20px;
            color: white;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        /* Added background image container */
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
            /* Using your specific image */
            background-image: url('../../images/DJI_0148.jpg');
        }
        
        /* Added overlay for better text readability */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8); /* Dark overlay for better text visibility */
            z-index: -1;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Added text shadow for better visibility */
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Added text shadow for better visibility */
        }
        
        .hero-btn {
            background: white;
            color: #3b82f6;
            padding: 14px 32px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 30px;
            text-align: center;
        }
        
        /* Gallery Scroll Styles */
        .gallery-scroll-container {
            width: 100%;
            overflow-x: auto;
            padding: 10px 0;
            scrollbar-width: thin;
            scrollbar-color: #3b82f6 #e5e7eb;
        }
        
        .gallery-scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .gallery-scroll-container::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 4px;
        }
        
        .gallery-scroll-container::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }
        
        .gallery-scroll-wrapper {
            display: flex;
            gap: 25px; /* Increased gap from 20px to 25px for better spacing */
            padding: 15px 10px; /* Increased padding for better visual spacing */
        }
        
        .gallery-card {
            flex: 0 0 auto;
            width: 280px;
            height: 200px;
            border-radius: 16px; /* Increased border radius for smoother look */
            overflow: hidden;
            position: relative;
            box-shadow: 0 6px 16px rgba(0,0,0,0.12); /* Enhanced shadow for better depth */
            transition: all 0.3s ease;
            background: white; /* Added white background */
            border: 1px solid #e5e7eb; /* Added subtle border */
        }
        
        .gallery-card:hover {
            transform: translateY(-8px); /* Increased hover effect */
            box-shadow: 0 16px 24px rgba(0,0,0,0.18); /* Enhanced shadow on hover */
        }
        
        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);
            padding: 24px 16px 16px; /* Increased padding */
            color: white;
        }
        
        .gallery-info {
            position: relative;
        }
        
        .gallery-title {
            font-size: 1.1rem; /* Slightly larger font */
            font-weight: 600;
            margin: 0 0 6px; /* Increased margin */
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .gallery-category {
            font-size: 0.85rem; /* Slightly larger font */
            margin: 0 0 10px; /* Increased margin */
            color: #93c5fd;
        }
        
        .photo-count {
            font-size: 0.85rem; /* Slightly larger font */
            background: rgba(59, 130, 246, 0.25); /* Slightly more opaque background */
            padding: 4px 10px; /* Increased padding */
            border-radius: 20px;
        }
        
        .gallery-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            color: #9ca3af;
        }
        
        .btn-outline-primary {
            border: 2px solid #3b82f6;
            color: #3b82f6;
            padding: 10px 20px; /* Increased padding */
            border-radius: 10px; /* Increased border radius */
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: #3b82f6;
            color: white;
        }
        
        /* Agenda Section */
        .agenda-section {
            margin: 60px 0;
        }
        
        .agenda-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .agenda-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .agenda-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .agenda-date {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .agenda-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        .agenda-description {
            color: #64748b;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .agenda-time {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #3b82f6;
            font-weight: 500;
        }
        
        /* Info Cards */
        .info-section {
            margin: 60px 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .info-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .info-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 28px;
        }
        
        .info-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }
        
        .info-description {
            color: #64748b;
            line-height: 1.6;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .gallery-card {
                width: 250px; /* Slightly smaller on mobile */
                height: 180px;
            }
            
            .gallery-scroll-wrapper {
                gap: 20px; /* Adjusted gap for mobile */
            }
            
            .navbar {
                padding: 0 20px;
            }
            
            .container {
                padding: 0 20px;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .gallery-card {
                width: 230px;
                height: 160px;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
            
            .brand-text h1 {
                font-size: 20px;
            }
            
            .brand-text p {
                font-size: 12px;
            }
        }
        
        /* Footer Styles */
        .footer {
            background: #1e293b;
            color: #cbd5e1;
            padding: 60px 0 30px;
            margin-top: 60px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-branding .branding {
            margin-bottom: 20px;
        }
        
        .footer-branding .brand-text h3 {
            color: white;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .footer-branding .brand-text p {
            color: #94a3b8;
            font-size: 14px;
        }
        
        /* Added brand-icon styling for footer to match other pages */
        .footer .brand-icon {
            width: 60px;
            height: 60px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .footer .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .footer-description {
            line-height: 1.6;
            max-width: 350px;
        }
        
        .footer-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
        }
        
        .footer-section h4 {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-section h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: #3b82f6;
            border-radius: 2px;
        }
        
        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-section ul li {
            margin-bottom: 12px;
        }
        
        .footer-section ul li a {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .footer-section ul li a:hover {
            color: #3b82f6;
            transform: translateX(5px);
        }
        
        .footer-section ul li i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: #3b82f6;
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
        }
        
        /* Responsive Footer */
        @media (max-width: 768px) {
            .footer {
                padding: 40px 0 20px;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .footer-links {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .footer-links {
                grid-template-columns: 1fr;
            }
            
            .social-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <nav class="navbar">
            <div class="branding">
                <div class="brand-icon">
                    <img src="{{ asset('images/LOGO_SMKN_4.png') }}" alt="SMKN 4 Bogor Logo">
                </div>
                <div class="brand-text">
                    <h1>SMKN 4</h1>
                    <p>Bogor</p>
                </div>
            </div>
            <div class="nav-menu">
                <ul class="nav-links">
                    <li><a href="{{ route('user.dashboard') }}" class="active">Beranda</a></li>
                    <li><a href="{{ route('user.gallery') }}">Galeri</a></li>
                    <li><a href="{{ route('user.informasi') }}">Informasi</a></li>
                    <li><a href="{{ route('user.agenda') }}">Agenda</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="hero-background"></div>
                <div class="hero-overlay"></div>
                <h1 class="hero-title">Selamat Datang di SMKN 4 BOGOR</h1>
                <p class="hero-subtitle">Mengembangkan potensi siswa melalui pendidikan berkualitas dan fasilitas modern</p>
                <a href="{{ route('user.gallery') }}" class="hero-btn">
                    <i class="fas fa-images"></i> Lihat Galeri
                </a>
            </section>

            <!-- Latest Gallery Section -->
            <section class="gallery-section">
                <h2 class="section-title">Galeri Terbaru</h2>
                <div class="gallery-scroll-container">
                    <div class="gallery-scroll-wrapper">
                        @forelse($latestGalleries as $gallery)
                        <div class="gallery-card">
                            @if($gallery->fotos->count() > 0)
                                <img src="{{ asset('uploads/galeri/' . $gallery->fotos->first()->file) }}" alt="{{ $gallery->post->judul ?? 'Gallery Image' }}" class="gallery-image">
                                <div class="gallery-overlay">
                                    <div class="gallery-info">
                                        <h3 class="gallery-title">{{ $gallery->post->judul ?? 'Untitled' }}</h3>
                                        <span class="gallery-category">{{ $gallery->post->kategori->judul ?? 'Umum' }}</span>
                                        <span class="photo-count">{{ $gallery->fotos->count() }} foto</span>
                                    </div>
                                </div>
                            @else
                                <div class="gallery-placeholder">
                                    <i class="fas fa-image" style="font-size: 40px; margin-bottom: 10px;"></i>
                                    <span>No Image</span>
                                </div>
                            @endif
                        </div>
                        @empty
                        <div class="gallery-card">
                            <div class="gallery-placeholder">
                                <i class="fas fa-images" style="font-size: 40px; margin-bottom: 10px;"></i>
                                <span>No galleries available</span>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ route('user.gallery') }}" class="btn-outline-primary">
                        <i class="fas fa-arrow-right"></i> Lihat Semua Galeri
                    </a>
                </div>
            </section>

            <!-- Latest Agenda Section -->
            <section class="agenda-section">
                <h2 class="section-title">Agenda Terbaru</h2>
                <div class="agenda-grid">
                    @forelse($latestAgendas as $agenda)
                    <div class="agenda-card">
                        <div class="agenda-date">{{ $agenda->date_label }}</div>
                        <h3 class="agenda-title">{{ $agenda->title }}</h3>
                        <p class="agenda-description">{{ $agenda->description }}</p>
                        <div class="agenda-time">
                            <i class="fas fa-clock"></i>
                            <span>{{ $agenda->time }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-500">Belum ada agenda tersedia.</p>
                    </div>
                    @endforelse
                </div>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ route('user.agenda') }}" class="btn-outline-primary">
                        <i class="fas fa-arrow-right"></i> Lihat Semua Agenda
                    </a>
                </div>
            </section>

            <!-- Info Cards Section -->

        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-branding">
                    <div class="branding">
                        <div class="brand-icon">
                            <img src="{{ asset('images/LOGO_SMKN_4.png') }}" alt="SMKN 4 Bogor Logo">
                        </div>
                        <div class="brand-text">
                            <h3>SMKN 4</h3>
                            <p>Bogor</p>
                        </div>
                    </div>
                    <p class="footer-description">
                        Sekolah Menengah Kejuruan Negeri 4 Bogor adalah lembaga pendidikan kejuruan yang berkomitmen untuk menghasilkan lulusan yang kompeten dan berkarakter.
                    </p>
                </div>
                
                <div class="footer-links">
                    <div class="footer-section">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="{{ route('user.dashboard') }}">Beranda</a></li>
                            <li><a href="{{ route('user.gallery') }}">Galeri</a></li>
                            <li><a href="{{ route('user.informasi') }}">Informasi</a></li>
                            <li><a href="{{ route('user.agenda') }}">Agenda</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h4>Kontak</h4>
                        <ul>
                            <li><i class="fas fa-map-marker-alt"></i> Jl. Raya Tajur No. 84, Kota Bogor</li>
                            <li><i class="fas fa-phone"></i> (0251) 1234567</li>
                            <li><i class="fas fa-envelope"></i> info@smkn4bogor.sch.id</li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h4>Ikuti Kami</h4>
                        <div class="social-links">
                            <a href="{{ \App\Models\SiteSetting::get('social_facebook', '#') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="{{ \App\Models\SiteSetting::get('social_instagram', '#') }}" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="{{ \App\Models\SiteSetting::get('social_youtube', '#') }}" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} SMKN 4 Bogor. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>