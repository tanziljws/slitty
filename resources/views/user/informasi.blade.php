<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Sekolah - SMKN 4 BOGOR</title>
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
        
        /* Main Content */
        .main-content {
            padding: 60px 0;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .page-title { 
            font-size: 2rem; 
            font-weight: 700; 
            color: #1e293b; 
            margin-bottom: 15px; 
        }
        
        .page-subtitle { 
            font-size: 1.1rem; 
            color: #64748b; 
            max-width: 600px; 
            margin: 0 auto; 
        }
        
        /* Profile Section */
        .profile-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .profile-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .profile-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #374151;
        }
        
        .profile-content p {
            margin-bottom: 20px;
        }
        
        /* Contact Section */
        .contact-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .contact-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background: #dbeafe;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .contact-icon i {
            font-size: 20px;
            color: #3b82f6;
        }
        
        .contact-details h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .contact-details p, .contact-details a {
            font-size: 1rem;
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .contact-details a:hover {
            color: #3b82f6;
        }
        
        .map-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            position: relative;
            border: 1px solid #e2e8f0;
        }
        
        .map-container:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .map-link::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: transparent;
            transition: background 0.3s ease;
        }
        
        .map-link:hover::after {
            background: rgba(0, 0, 0, 0.03);
        }
        
        .map-overlay {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: #1e293b;
            padding: 10px 16px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        
        .map-overlay i {
            color: #3b82f6;
        }
        
        .map-link:hover .map-overlay {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .map-container iframe {
            width: 100%;
            height: 450px;
            border: none;
            display: block;
        }
        
        /* Responsive map */
        @media (max-width: 768px) {
            .map-container iframe {
                height: 350px;
            }
            
            .map-overlay {
                top: 15px;
                right: 15px;
                padding: 8px 14px;
                font-size: 13px;
            }
        }
        
        @media (max-width: 480px) {
            .map-container iframe {
                height: 300px;
            }
        }
        
        /* Facilities Section */
        .facilities-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .facilities-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .facility-card {
            display: flex;
            gap: 20px;
            padding: 20px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .facility-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }
        
        .facility-icon {
            width: 50px;
            height: 50px;
            background: #dbeafe;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .facility-icon i {
            font-size: 24px;
            color: #3b82f6;
        }
        
        .facility-content h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        .facility-content p {
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }
        
        /* Achievements Section */
        .achievements-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .achievements-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .achievements-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .achievement-card {
            padding: 20px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .achievement-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }
        
        .achievement-card h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .achievement-card h4 i {
            color: #f59e0b;
        }
        
        .achievement-card p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .achievement-date {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
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
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar {
                padding: 0 20px;
            }
            
            .container {
                padding: 0 20px;
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
            }
            
            .facilities-grid {
                grid-template-columns: 1fr;
            }
            
            .achievements-list {
                grid-template-columns: 1fr;
            }
            
            .brand-text h1 {
                font-size: 20px;
            }
            
            .brand-text p {
                font-size: 12px;
            }
        }
        
        @media (max-width: 576px) {
            .facility-card {
                flex-direction: column;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .profile-title, .facilities-title, .achievements-title {
                font-size: 1.5rem;
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
                    <li><a href="{{ route('user.dashboard') }}">Beranda</a></li>
                    <li><a href="{{ route('user.gallery') }}">Galeri</a></li>
                    <li><a href="{{ route('user.informasi') }}" class="active">Informasi</a></li>
                    <li><a href="{{ route('user.agenda') }}">Agenda</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Informasi Sekolah</h1>
                <p class="page-subtitle">Informasi lengkap tentang SMKN 4 Bogor</p>
            </div>
            
            <!-- Profile Section -->
            <section class="profile-section">
                <h2 class="profile-title">{{ \App\Models\SiteSetting::get('profile_title', 'Profil SMKN 4 BOGOR') }}</h2>
                <div class="profile-content">
                    {!! \App\Models\SiteSetting::get('profile_content', '<p>SMK Negeri 4 Bogor adalah salah satu Sekolah Menengah Kejuruan Negeri yang terletak di Kota Bogor, Jawa Barat. Sekolah ini berdiri sejak tahun 1985 dan telah meluluskan ribuan siswa yang siap bekerja di dunia industri.</p><p>Dengan fasilitas yang lengkap dan tenaga pengajar yang profesional, SMKN 4 Bogor siap mencetak lulusan yang kompeten di bidangnya masing-masing.</p>') !!}
                </div>
            </section>
            
            <!-- Program Expertise Section -->
            <section class="facilities-section">
                <h2 class="facilities-title">{{ \App\Models\SiteSetting::get('expertise_title', 'Program Keahlian') }}</h2>
                <div class="facilities-grid">
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('expertise_1_name', 'Rekayasa Perangkat Lunak') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('expertise_1_description', 'Mempelajari pengembangan perangkat lunak, pemrograman, dan teknologi informasi terkini.') }}</p>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('expertise_2_name', 'Teknik Komputer dan Jaringan') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('expertise_2_description', 'Fokus pada perakitan komputer, jaringan komputer, dan keamanan sistem.') }}</p>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('expertise_3_name', 'Multimedia') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('expertise_3_description', 'Mengembangkan keterampilan dalam desain grafis, animasi, dan produksi media.') }}</p>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('expertise_4_name', 'Otomatisasi dan Tata Kelola Perkantoran') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('expertise_4_description', 'Menguasai keterampilan administrasi perkantoran dan teknologi informasi.') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section class="contact-section">
                <h2 class="contact-title">{{ \App\Models\SiteSetting::get('contact_title', 'Hubungi Kami') }}</h2>
                <div class="contact-grid">
                    <div class="contact-info">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Alamat</h4>
                                <p>{{ \App\Models\SiteSetting::get('contact_address', 'Jl. Raya Tajur No. 84, Kota Bogor, Jawa Barat 16134') }}</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Telepon</h4>
                                <p>{{ \App\Models\SiteSetting::get('contact_phone', '(0251) 1234567') }}</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Email</h4>
                                <p>{{ \App\Models\SiteSetting::get('contact_email', 'info@smkn4bogor.sch.id') }}</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Website</h4>
                                <p>{{ \App\Models\SiteSetting::get('contact_website', 'www.smkn4bogor.sch.id') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="map-container">
                        @php
                            $mapEmbed = \App\Models\SiteSetting::get('contact_map_embed', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.358216505442!2d106.79727831532822!3d-6.597005365229759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5d9c8b5b5b5%3A0x5b4b5b5b5b5b5b5b!2sSMKN%204%20Bogor!5e0!3m2!1sen!2sid!4v1678886405123!5m2!1sen!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');
                            $mapUrl = '#';
                            
                            // Function to parse coordinates from various formats
                            function parseCoordinates($text) {
                                // Format: lat,lng
                                if (preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', trim($text), $matches)) {
                                    $coords = explode(',', trim($text));
                                    return ['lat' => trim($coords[0]), 'lng' => trim($coords[1])];
                                }
                                
                                // Format: lat lng
                                if (preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),?\s+[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', trim($text), $matches)) {
                                    $coords = preg_split('/[,\s]+/', trim($text));
                                    if (count($coords) >= 2) {
                                        return ['lat' => trim($coords[0]), 'lng' => trim($coords[1])];
                                    }
                                }
                                
                                return null;
                            }
                            
                            // Check if it's already an iframe
                            if (strpos($mapEmbed, '<iframe') !== false) {
                                // Extract URL from iframe for clickable link
                                preg_match('/src="([^"]+)"/', $mapEmbed, $matches);
                                $mapUrl = isset($matches[1]) ? $matches[1] : '#';
                            } 
                            // Check if it's a Google Maps URL
                            elseif (filter_var($mapEmbed, FILTER_VALIDATE_URL)) {
                                $mapUrl = $mapEmbed;
                                
                                // Check if it's a Google Maps share link
                                if (strpos($mapEmbed, 'maps.app.goo.gl') !== false) {
                                    // Convert short URL to embed URL
                                    $mapEmbed = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.358216505442!2d106.79727831532822!3d-6.597005365229759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5d9c8b5b5b5%3A0x5b4b5b5b5b5b5b5b!2sSMKN%204%20Bogor!5e0!3m2!1sen!2sid!4v1678886405123!5m2!1sen!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
                                } else if (strpos($mapEmbed, 'google.com/maps') !== false) {
                                    // For regular Google Maps URLs, convert to embed
                                    // Extract coordinates from URL if possible
                                    $embedUrl = str_replace('/maps', '/maps/embed', $mapEmbed);
                                    $mapEmbed = '<iframe src="' . $embedUrl . '" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
                                } else {
                                    // For other URLs, show as a link
                                    $mapEmbed = '<div style="height:450px;display:flex;align-items:center;justify-content:center;background:#f0f0f0;border:1px solid #ddd;border-radius:16px;">
                                                    <div style="text-align:center;">
                                                        <i class="fas fa-link" style="font-size:48px;color:#4285f4;margin-bottom:15px;"></i>
                                                        <p>Link: <a href="' . $mapEmbed . '" target="_blank">' . $mapEmbed . '</a></p>
                                                    </div>
                                                </div>';
                                }
                            }
                            // If it's plain text coordinates, try to convert to map
                            elseif ($coords = parseCoordinates($mapEmbed)) {
                                // It's coordinates, convert to Google Maps embed
                                $lat = $coords['lat'];
                                $lng = $coords['lng'];
                                $mapEmbed = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.358216505442!2d' . $lng . '!3d' . $lat . '!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z' . $lat . 'N%20' . $lng . 'E!5e0!3m2!1sen!2s!4v1678886405123!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
                                $mapUrl = 'https://www.google.com/maps?q=' . $lat . ',' . $lng;
                            }
                            // If it's neither, treat as plain text
                            else {
                                $mapEmbed = '<div style="height:450px;display:flex;align-items:center;justify-content:center;background:#f0f0f0;border:1px solid #ddd;border-radius:16px;">
                                                <div style="text-align:center;padding:20px;">
                                                    <i class="fas fa-map-marked-alt" style="font-size:48px;color:#4285f4;margin-bottom:15px;"></i>
                                                    <h3 style="margin-bottom:10px;">Lokasi Sekolah</h3>
                                                    <p style="margin-bottom:15px;">' . e($mapEmbed) . '</p>
                                                    <p style="font-size:14px;color:#666;">Untuk menampilkan peta interaktif, masukkan URL Google Maps atau koordinat pada pengaturan situs.</p>
                                                </div>
                                            </div>';
                            }
                        @endphp
                        <a href="{{ $mapUrl }}" target="_blank" class="block map-link">
                            {!! $mapEmbed !!}
                            <div class="map-overlay">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Lihat di Google Maps</span>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Facilities Section -->
            <section class="facilities-section">
                <h2 class="facilities-title">{{ \App\Models\SiteSetting::get('facilities_title', 'Fasilitas Sekolah') }}</h2>
                <div class="facilities-grid">
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="{{ \App\Models\SiteSetting::get('facility_1_icon', 'fas fa-laptop') }}"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('facility_1_title', 'Laboratorium Komputer') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('facility_1_description', 'Ruang laboratorium modern dengan komputer terbaru untuk mendukung pembelajaran teknologi informasi.') }}</p>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="{{ \App\Models\SiteSetting::get('facility_2_icon', 'fas fa-flask') }}"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('facility_2_title', 'Laboratorium IPA') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('facility_2_description', 'Fasilitas laboratorium sains lengkap dengan peralatan modern untuk eksperimen fisika, kimia, dan biologi.') }}</p>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="{{ \App\Models\SiteSetting::get('facility_3_icon', 'fas fa-book') }}"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('facility_3_title', 'Perpustakaan Digital') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('facility_3_description', 'Perpustakaan dengan koleksi buku dan akses digital untuk mendukung kegiatan belajar mengajar.') }}</p>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="{{ \App\Models\SiteSetting::get('facility_4_icon', 'fas fa-dumbbell') }}"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('facility_4_title', 'Fasilitas Olahraga') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('facility_4_description', 'Lapangan olahraga dan fasilitas pendukung untuk kegiatan ekstrakurikuler dan kompetisi.') }}</p>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="{{ \App\Models\SiteSetting::get('facility_5_icon', 'fas fa-utensils') }}"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('facility_5_title', 'Kantin Sekolah') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('facility_5_description', 'Kantin yang menyediakan makanan sehat dan bergizi untuk siswa dan staf pengajar.') }}</p>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="{{ \App\Models\SiteSetting::get('facility_6_icon', 'fas fa-wifi') }}"></i>
                        </div>
                        <div class="facility-content">
                            <h4>{{ \App\Models\SiteSetting::get('facility_6_title', 'Wi-Fi Sekolah') }}</h4>
                            <p>{{ \App\Models\SiteSetting::get('facility_6_description', 'Akses internet cepat di seluruh area sekolah untuk mendukung pembelajaran digital.') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Achievements Section -->
            <section class="achievements-section">
                <h2 class="achievements-title">{{ \App\Models\SiteSetting::get('achievements_title', 'Prestasi Sekolah') }}</h2>
                <div class="achievements-list">
                    <div class="achievement-card">
                        <h4><i class="{{ \App\Models\SiteSetting::get('achievement_1_icon', 'fas fa-trophy') }}"></i> {{ \App\Models\SiteSetting::get('achievement_1_title', 'Juara 1 LKS Provinsi 2023') }}</h4>
                        <p>{{ \App\Models\SiteSetting::get('achievement_1_description', 'Tim siswa meraih juara pertama dalam Lomba Kompetensi Siswa tingkat provinsi untuk bidang Teknologi Informasi.') }}</p>
                        <span class="achievement-date">{{ \App\Models\SiteSetting::get('achievement_1_date', 'Oktober 2023') }}</span>
                    </div>
                    
                    <div class="achievement-card">
                        <h4><i class="{{ \App\Models\SiteSetting::get('achievement_2_icon', 'fas fa-medal') }}"></i> {{ \App\Models\SiteSetting::get('achievement_2_title', 'Juara 2 Olimpiade Matematika') }}</h4>
                        <p>{{ \App\Models\SiteSetting::get('achievement_2_description', 'Siswa kami berhasil meraih medali perak dalam Olimpiade Matematika tingkat nasional.') }}</p>
                        <span class="achievement-date">{{ \App\Models\SiteSetting::get('achievement_2_date', 'September 2023') }}</span>
                    </div>
                    
                    <div class="achievement-card">
                        <h4><i class="{{ \App\Models\SiteSetting::get('achievement_3_icon', 'fas fa-award') }}"></i> {{ \App\Models\SiteSetting::get('achievement_3_title', 'Sekolah Adiwiyata Mandiri') }}</h4>
                        <p>{{ \App\Models\SiteSetting::get('achievement_3_description', 'Penghargaan dari Kementerian Lingkungan Hidup sebagai sekolah peduli dan berbudaya lingkungan.') }}</p>
                        <span class="achievement-date">{{ \App\Models\SiteSetting::get('achievement_3_date', 'Agustus 2023') }}</span>
                    </div>
                    
                    <div class="achievement-card">
                        <h4><i class="{{ \App\Models\SiteSetting::get('achievement_4_icon', 'fas fa-certificate') }}"></i> {{ \App\Models\SiteSetting::get('achievement_4_title', 'Sertifikasi Internasional') }}</h4>
                        <p>{{ \App\Models\SiteSetting::get('achievement_4_description', 'Lebih dari 200 siswa lulus dengan sertifikasi internasional dalam bidang teknologi informasi.') }}</p>
                        <span class="achievement-date">{{ \App\Models\SiteSetting::get('achievement_4_date', 'Juli 2023') }}</span>
                    </div>
                    
                    <div class="achievement-card">
                        <h4><i class="{{ \App\Models\SiteSetting::get('achievement_5_icon', 'fas fa-chess') }}"></i> {{ \App\Models\SiteSetting::get('achievement_5_title', 'Juara 1 Turnamen Catur') }}</h4>
                        <p>{{ \App\Models\SiteSetting::get('achievement_5_description', 'Tim catur sekolah meraih juara pertama dalam turnamen antar sekolah se-Kota Bogor.') }}</p>
                        <span class="achievement-date">{{ \App\Models\SiteSetting::get('achievement_5_date', 'Juni 2023') }}</span>
                    </div>
                    
                    <div class="achievement-card">
                        <h4><i class="{{ \App\Models\SiteSetting::get('achievement_6_icon', 'fas fa-music') }}"></i> {{ \App\Models\SiteSetting::get('achievement_6_title', 'Festival Musik Pelajar') }}</h4>
                        <p>{{ \App\Models\SiteSetting::get('achievement_6_description', 'Grup band sekolah meraih penghargaan khusus dalam Festival Musik Pelajar tingkat provinsi.') }}</p>
                        <span class="achievement-date">{{ \App\Models\SiteSetting::get('achievement_6_date', 'Mei 2023') }}</span>
                    </div>
                </div>
            </section>
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