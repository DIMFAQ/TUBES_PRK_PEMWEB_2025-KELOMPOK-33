<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanSpot - Sistem Pelaporan Infrastruktur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Landing Page Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .landing-page {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #ffffff;
            overflow-x: hidden;
            color: #1a202c;
        }

        .landing-navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 1.25rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2.5rem;
        }

        .landing-navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .landing-logo {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #065f46;
            text-decoration: none;
            letter-spacing: -0.3px;
        }

        .landing-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .landing-nav-links {
            display: flex;
            gap: 2.8rem;
            align-items: center;
        }

        .landing-nav-links a {
            text-decoration: none;
            color: #4b5563;
            font-weight: 500;
            font-size: 0.94rem;
            transition: color 0.2s;
        }

        .landing-nav-links a:hover {
            color: #10b981;
        }

        .landing-btn {
            padding: 0.65rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .landing-btn-outline {
            border: 2px solid #e5e7eb;
            color: #4b5563;
            background: transparent;
        }

        .landing-btn-outline:hover {
            border-color: #10b981;
            color: #10b981;
            background: #f0fdf4;
        }

        .landing-btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: 2px solid transparent;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .landing-btn-primary:hover {
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transform: translateY(-1px);
        }

        .hero-section {
            margin-top: 70px;
            padding: 8rem 0 3rem;
            background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -10%;
            right: 5%;
            width: 600px;
            height: 600px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:%2310b981;stop-opacity:0.2" /><stop offset="100%" style="stop-color:%23059669;stop-opacity:0.1" /></linearGradient></defs><circle cx="100" cy="100" r="80" fill="url(%23grad1)"/></svg>');
            background-size: contain;
            opacity: 0.6;
            animation: float 6s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(180deg, transparent 0%, #ffffff 100%);
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 1;
            min-height: 600px;
        }

        .hero-text {
            text-align: left;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.3rem;
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border-radius: 50px;
            font-size: 0.87rem;
            font-weight: 600;
            margin-bottom: 2rem;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2.5rem;
            line-height: 1.7;
            max-width: 500px;
            font-weight: 400;
        }

        .hero-buttons {
            display: flex;
            gap: 1.2rem;
            align-items: center;
        }

        .hero-btn-large {
            padding: 1rem 2.5rem !important;
            font-size: 1rem !important;
            border-radius: 12px !important;
        }

        .hero-right {
            position: relative;
            height: 550px;
        }

        .hero-mascot {
            width: 380px;
            height: 380px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 40px 100px rgba(16, 185, 129, 0.4);
            z-index: 1;
        }

        .hero-mascot::before {
            content: '';
            position: absolute;
            width: 120%;
            height: 120%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.3; }
        }

        .hero-mascot i {
            font-size: 12rem;
            color: white;
            z-index: 2;
            position: relative;
        }

        .floating-card {
            position: absolute;
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: float 4s ease-in-out infinite;
            z-index: 3;
        }

        .floating-card-1 {
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .floating-card-2 {
            top: 60%;
            right: 5%;
            animation-delay: 1s;
        }

        .floating-card-3 {
            bottom: 10%;
            left: 10%;
            animation-delay: 2s;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .card-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #065f46;
            margin-bottom: 0.25rem;
        }

        .card-subtitle {
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Fast Stats Section */
        .fast-section {
            padding: 8rem 0;
            background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
            position: relative;
        }

        .fast-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .fast-text {
            text-align: left;
        }

        .fast-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #065f46;
            line-height: 1.3;
            letter-spacing: -1px;
            margin-bottom: 1.5rem;
        }

        .fast-stats {
            display: flex;
            gap: 3rem;
            margin-top: 3rem;
        }

        .fast-stat-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .fast-stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #10b981;
            margin-bottom: 0.5rem;
        }

        .fast-stat-label {
            font-size: 0.9rem;
            color: #6b7280;
        }

        /* Hire Section */
        .hire-section {
            padding: 8rem 0;
            background: white;
            position: relative;
        }

        .hire-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .hire-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #065f46;
            line-height: 1.3;
            letter-spacing: -1px;
            margin-bottom: 1.5rem;
        }

        .hire-desc {
            font-size: 1.1rem;
            color: #6b7280;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .hire-link {
            color: #10b981;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hire-visual {
            position: relative;
            height: 500px;
        }

        .hire-image-wrapper {
            width: 450px;
            height: 450px;
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            overflow: hidden;
        }

        /* Globe Section */
        .globe-section {
            padding: 0;
            background: white;
            position: relative;
            margin-top: 8rem;
        }

        .globe-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }

        .globe-curved-bg {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f2942 100%);
            border-radius: 60px;
            padding: 8rem 3rem;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .globe-curved-bg::before {
            content: '';
            position: absolute;
            top: -15%;
            left: 50%;
            transform: translateX(-50%);
            width: 400px;
            height: 400px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.2);
        }

        .globe-icon {
            position: absolute;
            top: -180px;
            left: 50%;
            transform: translateX(-50%);
            width: 320px;
            height: 320px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 30px 80px rgba(16, 185, 129, 0.4);
            z-index: 2;
        }

        .globe-icon i {
            font-size: 10rem;
            color: white;
        }

        .globe-content {
            position: relative;
            z-index: 1;
            padding-top: 8rem;
        }

        .globe-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.3;
            letter-spacing: -1px;
        }

        .globe-desc {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .globe-btn {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .globe-btn:hover {
            background: white;
            color: #065f46;
        }

        .hero-stats {
            display: flex;
            gap: 4rem;
            justify-content: center;
            margin-top: 6rem;
            padding-top: 4rem;
            border-top: 1px solid rgba(16, 185, 129, 0.15);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #10b981;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }

        .stat-label {
            font-size: 0.95rem;
            color: #6b7280;
            font-weight: 500;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Apply Animations */
        .hero-text {
            animation: slideInLeft 0.8s ease-out;
        }

        .hero-right {
            animation: slideInRight 0.8s ease-out;
        }

        .hero-badge {
            animation: fadeInUp 0.6s ease-out 0.2s backwards;
        }

        .hero-title {
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        .hero-subtitle {
            animation: fadeInUp 0.8s ease-out 0.6s backwards;
        }

        .hero-buttons {
            animation: fadeInUp 0.8s ease-out 0.8s backwards;
        }

        .fast-section {
            animation: fadeIn 1s ease-out;
        }

        .who-item {
            animation: fadeInUp 0.8s ease-out;
        }

        .feature-item {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Simplify floating cards */
        .floating-card-single {
            position: absolute;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 24px;
            padding: 1.5rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: float 4s ease-in-out infinite;
            z-index: 3;
        }

        .stats-grid {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .stat-mini {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .stat-mini-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }

        .stat-mini-value {
            font-size: 1.3rem;
            font-weight: 800;
            color: #065f46;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 28px;
            padding: 0;
            max-width: 900px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: fadeInUp 0.4s ease-out;
            display: grid;
            grid-template-columns: 45% 55%;
        }

        .modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: #f3f4f6;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 1.3rem;
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .modal-title {
            font-size: 2rem;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .modal-subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1.2rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #10b981;
            background: white;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .form-link {
            color: #10b981;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .form-link:hover {
            text-decoration: underline;
        }

        .modal-side {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .modal-side::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .modal-side::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }

        .modal-side-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            margin-bottom: 2rem;
            z-index: 2;
            position: relative;
        }

        .modal-side h3 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 1rem;
            z-index: 2;
            position: relative;
            text-align: center;
        }

        .modal-side p {
            text-align: center;
            font-size: 0.95rem;
            opacity: 0.9;
            line-height: 1.6;
            z-index: 2;
            position: relative;
        }

        .modal-form-side {
            padding: 3rem;
            overflow-y: auto;
        }

        .modal-close {
            top: 1.5rem;
            right: 1.5rem;
            z-index: 10;
        }

        @media (max-width: 768px) {
            .modal-content {
                grid-template-columns: 1fr;
                max-width: 450px;
            }
            .modal-side {
                display: none;
            }
            .modal-form-side {
                padding: 2.5rem 2rem;
            }
        }

        .who-section {
            padding: 10rem 0;
            background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
            position: relative;
        }

        .who-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M0,60 Q300,0 600,60 T1200,60 L1200,0 L0,0 Z" fill="%23ffffff"/></svg>');
            background-size: cover;
            transform: translateY(-1px);
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
            padding: 0 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .section-label {
            font-size: 0.82rem;
            color: #10b981;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin-bottom: 0.75rem;
            display: block;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #065f46;
            letter-spacing: -1px;
            line-height: 1.3;
            max-width: 700px;
            margin: 0 auto 1rem;
        }

        .section-subtitle {
            font-size: 1.05rem;
            color: #6b7280;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
            font-weight: 400;
        }

        .section-title-stacked {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: #065f46;
            letter-spacing: -0.5px;
            line-height: 1.3;
            margin: 0 auto;
            text-align: center;
            text-transform: uppercase;
            display: block;
            width: 100%;
        }

        .title-line-1,
        .title-line-2 {
            display: inline-block;
        }

        .who-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2.5rem;
            display: grid;
            gap: 8rem;
        }

        .who-item {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .who-item:nth-child(even) {
            grid-template-columns: 1fr 1.1fr;
        }

        .who-item:nth-child(even) .who-image {
            order: 2;
        }

        .who-image {
            position: relative;
        }

        .who-image img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 28px;
            box-shadow: 0 30px 80px rgba(16, 185, 129, 0.15);
        }

        .who-placeholder {
            width: 100%;
            height: 450px;
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 40px 100px rgba(16, 185, 129, 0.25);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }

        .who-placeholder::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 30%, rgba(16, 185, 129, 0.4) 0%, transparent 60%);
            top: 0;
            left: 0;
        }

        .who-placeholder::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .who-icon-wrapper {
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .who-icon-wrapper i {
            font-size: 5rem;
            color: white;
        }

        .who-content h3 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }

        .who-content p {
            font-size: 1.08rem;
            color: #6b7280;
            line-height: 1.85;
            margin-bottom: 2rem;
        }

        .who-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }

        .who-feature-item {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            color: #4b5563;
            font-size: 0.97rem;
            font-weight: 500;
        }

        .who-feature-item::before {
            content: '✓';
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .features-section {
            padding: 10rem 0;
            background: #ffffff;
            position: relative;
        }

        .features-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M0,60 Q300,120 600,60 T1200,60 L1200,0 L0,0 Z" fill="%23f9fafb"/></svg>');
            background-size: cover;
            transform: translateY(-1px);
        }

        .features-list {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 2.5rem;
        }

        .feature-item {
            display: flex;
            gap: 3rem;
            padding: 3.5rem 0;
            border-bottom: 1px solid #e5e7eb;
            align-items: flex-start;
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-number {
            font-size: 4rem;
            font-weight: 800;
            color: #d1fae5;
            min-width: 80px;
            line-height: 1;
            letter-spacing: -2px;
        }

        .feature-content {
            flex: 1;
            padding-top: 0.5rem;
        }

        .feature-content h3 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #065f46;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .feature-content p {
            font-size: 1.05rem;
            color: #6b7280;
            line-height: 1.8;
        }

        .feature-icon-right {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.25);
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon-right {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 20px 50px rgba(16, 185, 129, 0.35);
        }

        .cta-section {
            padding: 0;
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M0,60 Q300,0 600,60 T1200,60 L1200,0 L0,0 Z" fill="%23ffffff"/></svg>');
            background-size: cover;
            transform: translateY(-1px);
        }

        .cta-inner {
            padding: 12rem 0 10rem;
            position: relative;
        }

        .cta-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.1;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(16, 185, 129, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(5, 150, 105, 0.3) 0%, transparent 50%);
        }

        .cta-content {
            max-width: 750px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
            padding: 0 2.5rem;
        }

        .cta-title {
            font-size: 3.8rem;
            font-weight: 800;
            color: white;
            margin-bottom: 2rem;
            line-height: 1.15;
            letter-spacing: -1.5px;
        }

        .cta-subtitle {
            font-size: 1.22rem;
            color: rgba(255, 255, 255, 0.92);
            margin-bottom: 3rem;
            line-height: 1.7;
            font-weight: 400;
        }

        .cta-buttons {
            display: flex;
            gap: 1.2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .landing-btn-white {
            background: white;
            color: #065f46;
            padding: 1.1rem 3rem;
            font-size: 1.05rem;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .landing-btn-white:hover {
            background: #f0fdf4;
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .landing-footer {
            background: #064e3b;
            color: white;
            padding: 4rem 0 2.5rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2.5rem;
            text-align: center;
        }

        .footer-logo-section {
            margin-bottom: 2.5rem;
        }

        .footer-logo-section p {
            font-size: 1.02rem;
            line-height: 1.7;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.92rem;
            margin-top: 1.5rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin-top: 2.5rem;
            padding-top: 2.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.94rem;
            transition: color 0.2s;
            font-weight: 500;
        }

        .footer-links a:hover {
            color: white;
        }

        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .hero-text {
                text-align: center;
            }

            .hero-buttons {
                justify-content: center;
            }

            .who-item {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .who-item:nth-child(even) .who-image {
                order: 1;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1.5rem;
            }

            .landing-nav-links a:not(.landing-btn) {
                display: none;
            }

            .hero-section {
                padding: 5rem 0 3rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .section-title {
                font-size: 1.4rem;
                line-height: 1.4;
                padding: 0 1rem;
                display: block;
                max-width: 100%;
            }

            .section-label {
                font-size: 0.75rem;
                display: block;
            }

            .section-subtitle {
                font-size: 0.9rem;
                padding: 0 1rem;
                display: block;
            }

            .who-content h3 {
                font-size: 1.5rem;
            }

            .cta-title {
                font-size: 2rem;
            }

            .floating-badge {
                display: none;
            }

            .who-section, .features-section, .cta-section {
                padding: 4rem 0;
            }

            .features-section::before,
            .who-section::before {
                display: none;
            }

            .section-header {
                margin-bottom: 2.5rem;
                display: flex;
                visibility: visible;
                opacity: 1;
                position: relative;
                z-index: 10;
            }

            .section-title-stacked {
                font-size: 1.5rem;
                line-height: 1.4;
                padding: 0 1rem;
                max-width: 100%;
            }

            .title-line-1 {
                display: none;
            }

            .title-line-2 {
                font-size: 1.5rem;
                display: block;
            }

            .hero-section {
                background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
                position: relative;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                width: 500px;
                height: 500px;
                background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
                border-radius: 50%;
                top: -200px;
                right: -200px;
                z-index: 0;
            }

            .hero-section::after {
                display: none;
            }

            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 0 1.5rem;
                min-height: auto;
                gap: 0;
            }

            .hero-text {
                max-width: 100%;
                text-align: center;
            }

            .hero-right {
                display: none;
            }

            .hero-buttons {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }

            .hero-btn-large {
                width: 100%;
                justify-content: center;
            }

            .who-grid {
                gap: 4rem;
            }

            .who-item {
                gap: 2rem;
                flex-direction: column;
                text-align: center;
            }

            .who-image {
                max-width: 100%;
            }

            .who-image {
                display: none;
            }

            .who-grid {
                gap: 2rem;
            }

            .who-item {
                padding: 1.8rem 1.5rem;
                background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
                border-radius: 20px;
                border: 2px solid #10b981;
                display: block;
                grid-template-columns: 1fr;
            }

            .who-content {
                padding: 0;
            }

            .who-content h3 {
                font-size: 1.5rem;
                margin-bottom: 0.9rem;
                font-weight: 800;
            }

            .who-content p {
                font-size: 0.92rem;
                line-height: 1.6;
                margin-bottom: 1.1rem;
            }

            .who-features {
                gap: 0.7rem;
                grid-template-columns: 1fr;
            }

            .who-feature-item {
                font-size: 0.88rem;
                padding: 0;
                gap: 0.7rem;
            }

            .who-feature-item::before {
                min-width: 26px;
                width: 26px;
                height: 26px;
                font-size: 0.8rem;
            }

            .who-content h3 {
                font-size: 1.8rem;
            }

            .who-features {
                grid-template-columns: 1fr;
            }

            .feature-item {
                padding: 1.5rem 1.2rem;
                gap: 1rem;
            }

            .feature-number {
                font-size: 2rem;
                min-width: 50px;
            }

            .feature-content h3 {
                font-size: 1.2rem;
                margin-bottom: 0.5rem;
            }

            .feature-content p {
                font-size: 0.9rem;
                line-height: 1.5;
            }

            .feature-icon-right {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .landing-navbar {
                padding: 1rem 0;
            }

            .landing-logo {
                font-size: 1.3rem;
            }

            .landing-logo-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 1rem;
            }

            .hero-section {
                padding: 3rem 0 2.5rem;
            }

            .hero-content {
                padding: 0 1rem;
            }

            .hero-title {
                font-size: 1.75rem;
                line-height: 1.3;
                margin-bottom: 1rem;
            }

            .hero-subtitle {
                font-size: 0.9rem;
                line-height: 1.6;
                margin-bottom: 2rem;
                max-width: 100%;
            }

            .hero-badge {
                font-size: 0.75rem;
                padding: 0.5rem 1rem;
                margin-bottom: 1.5rem;
            }

            .who-section, .features-section, .cta-section {
                padding: 3rem 0;
            }

            .features-section::before,
            .who-section::before {
                display: none;
            }

            .section-header {
                margin-bottom: 2rem;
                display: flex;
                visibility: visible;
                opacity: 1;
                position: relative;
                z-index: 10;
            }

            .section-title-stacked {
                font-size: 1.3rem;
                line-height: 1.4;
            }

            .title-line-1 {
                display: none;
            }

            .title-line-2 {
                font-size: 1.3rem;
            }

            .landing-btn {
                padding: 0.65rem 1.3rem;
                font-size: 0.85rem;
            }

            .who-grid {
                gap: 1.5rem;
            }

            .who-item {
                padding: 1.5rem 1.2rem;
                text-align: center;
                border-radius: 18px;
            }

            .who-content h3 {
                font-size: 1.3rem;
                margin-bottom: 0.7rem;
            }

            .who-content p {
                font-size: 0.88rem;
                margin-bottom: 0.9rem;
                line-height: 1.6;
            }

            .who-feature-item {
                font-size: 0.85rem;
                padding: 0;
                gap: 0.6rem;
            }

            .who-feature-item::before {
                min-width: 24px;
                width: 24px;
                height: 24px;
                font-size: 0.75rem;
            }

            .feature-item {
                padding: 1.2rem 1rem;
            }

            .feature-number {
                font-size: 1.6rem;
                min-width: 45px;
            }

            .feature-content h3 {
                font-size: 1.1rem;
            }

            .feature-content p {
                font-size: 0.85rem;
                line-height: 1.5;
            }

            .feature-icon-right {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .section-title {
                font-size: 1.2rem;
                line-height: 1.4;
                display: block;
                max-width: 100%;
            }

            .section-label {
                font-size: 0.7rem;
                display: block;
            }

            .section-subtitle {
                font-size: 0.85rem;
                display: block;
            }
        }
    </style>
</head>
<body class="landing-page">
    <!-- Navbar -->
    <nav class="landing-navbar">
        <div class="container">
            <a href="#" class="landing-logo">
                <div class="landing-logo-icon"><i class="fas fa-leaf"></i></div>
                CleanSpot
            </a>
            <div class="landing-nav-links">
                <a href="#who">Layanan</a>
                <a href="#features">Fitur</a>
                <a href="#cta">Tentang</a>
                <button onclick="openLoginModal()" class="landing-btn landing-btn-outline">Masuk</button>
                <button onclick="openRegisterModal()" class="landing-btn landing-btn-primary">Daftar</button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <i class="fas fa-shield-alt"></i>
                    Platform Pelaporan Infrastruktur Terpadu
                </div>
                <h1 class="hero-title">
                    Lapor Dan Pantau<br>
                    Infrastruktur Kota<br>
                    <span class="highlight">Secara Real-Time</span>
                </h1>
                <p class="hero-subtitle">
                    CleanSpot menghubungkan warga, petugas lapangan, dan pemerintah dalam satu sistem 
                    untuk penanganan infrastruktur yang lebih cepat dan efisien.
                </p>
                <div class="hero-buttons">
                    <button onclick="openRegisterModal()" class="landing-btn landing-btn-primary hero-btn-large">
                        Buat Akun <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                    </button>
                    <button onclick="openLoginModal()" class="landing-btn landing-btn-outline hero-btn-large">
                        Masuk
                    </button>
                </div>
            </div>
            
            <!-- Hero Visual -->
            <div class="hero-right">
                <div class="hero-mascot">
                    <i class="fas fa-leaf"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Serve Section -->
    <section class="who-section" id="who">
        <div class="section-header">
            <h2 class="section-title-stacked">
                <span class="title-line-1">SIAPA YANG KAMI LAYANI</span><br>
                <span class="title-line-2">Tiga Peran, Satu Tujuan</span>
            </h2>
        </div>

        <div class="who-grid">
            <!-- Warga -->
            <div class="who-item">
                <div class="who-image">
                    <div class="who-placeholder">
                        <div class="who-icon-wrapper">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="who-content">
                    <h3>Warga</h3>
                    <p>
                        Warga dapat melaporkan masalah infrastruktur dengan mudah dan cepat melalui platform kami. 
                        Cukup ambil foto, tandai lokasi menggunakan GPS, dan kirimkan laporan Anda dalam hitungan detik. 
                        Pantau progress penanganan secara real-time dan dapatkan notifikasi otomatis setiap ada update dari petugas.
                    </p>
                    <div class="who-features">
                        <div class="who-feature-item">Buat laporan dengan foto & GPS</div>
                        <div class="who-feature-item">Tracking status real-time</div>
                        <div class="who-feature-item">Riwayat laporan lengkap</div>
                        <div class="who-feature-item">Notifikasi setiap update</div>
                    </div>
                </div>
            </div>

            <!-- Petugas -->
            <div class="who-item">
                <div class="who-image">
                    <div class="who-placeholder">
                        <div class="who-icon-wrapper">
                            <i class="fas fa-hard-hat"></i>
                        </div>
                    </div>
                </div>
                <div class="who-content">
                    <h3>Petugas Lapangan</h3>
                    <p>
                        Petugas lapangan menerima penugasan secara otomatis berdasarkan lokasi terdekat dan keahlian yang dimiliki. 
                        Dashboard khusus memudahkan manajemen tugas, update progress pekerjaan secara real-time, 
                        dan komunikasi efektif dengan sistem untuk penanganan infrastruktur yang lebih cepat dan terkoordinasi.
                    </p>
                    <div class="who-features">
                        <div class="who-feature-item">Tugas otomatis sesuai lokasi</div>
                        <div class="who-feature-item">Update status real-time</div>
                        <div class="who-feature-item">Dashboard manajemen lengkap</div>
                        <div class="who-feature-item">Riwayat penyelesaian detail</div>
                    </div>
                </div>
            </div>

            <!-- Admin -->
            <div class="who-item">
                <div class="who-image">
                    <div class="who-placeholder">
                        <div class="who-icon-wrapper">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>
                <div class="who-content">
                    <h3>Administrator</h3>
                    <p>
                        Administrator memiliki kontrol penuh terhadap sistem pelaporan infrastruktur. 
                        Melakukan verifikasi dan validasi setiap laporan yang masuk, menugaskan petugas lapangan yang tepat, 
                        memonitor semua aktivitas sistem secara real-time, dan mengakses statistik serta analytics lengkap 
                        untuk mendukung pengambilan keputusan yang lebih baik dan akurat.
                    </p>
                    <div class="who-features">
                        <div class="who-feature-item">Verifikasi laporan masuk</div>
                        <div class="who-feature-item">Penugasan petugas optimal</div>
                        <div class="who-feature-item">Monitoring aktivitas sistem</div>
                        <div class="who-feature-item">Analytics & reporting lengkap</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="section-header">
            <div class="section-label">FITUR UNGGULAN</div>
            <h2 class="section-title">Solusi Lengkap untuk Penanganan Infrastruktur</h2>
            <p class="section-subtitle">Teknologi modern untuk memudahkan pelaporan dan pengelolaan sampah secara real-time</p>
        </div>

        <div class="features-list">
            <div class="feature-item">
                <div class="feature-number">01</div>
                <div class="feature-content">
                    <h3>Peta Interaktif Real-time</h3>
                    <p>
                        Visualisasi semua laporan pada peta interaktif dengan marker berbeda untuk setiap status. 
                        Lihat lokasi infrastruktur bermasalah di sekitar Anda secara real-time.
                    </p>
                </div>
                <div class="feature-icon-right">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-number">02</div>
                <div class="feature-content">
                    <h3>Smart Assignment System</h3>
                    <p>
                        Sistem otomatis menugaskan petugas terdekat berdasarkan lokasi, ketersediaan, 
                        dan keahlian untuk respon yang lebih cepat dan efisien.
                    </p>
                </div>
                <div class="feature-icon-right">
                    <i class="fas fa-bolt"></i>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-number">03</div>
                <div class="feature-content">
                    <h3>Status Tracking Transparan</h3>
                    <p>
                        Pantau setiap tahap penanganan dari laporan masuk, verifikasi, penugasan, 
                        hingga penyelesaian dengan timeline yang jelas.
                    </p>
                </div>
                <div class="feature-icon-right">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-number">04</div>
                <div class="feature-content">
                    <h3>Analytics & Reporting</h3>
                    <p>
                        Dashboard analytics lengkap dengan statistik laporan, performa petugas, 
                        dan insight untuk pengambilan keputusan yang lebih baik.
                    </p>
                </div>
                <div class="feature-icon-right">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="cta">
        <div class="cta-bg-pattern"></div>
        <div class="cta-inner">
            <div class="cta-content">
                <h2 class="cta-title">
                    Mulai Berkontribusi untuk<br>
                    Kota yang Lebih Baik
                </h2>
                <p class="cta-subtitle">
                    Bergabunglah dengan ratusan warga yang telah menggunakan CleanSpot untuk 
                    membuat lingkungan lebih baik. Daftar gratis dan mulai laporkan hari ini.
                </p>
                <div class="cta-buttons">
                    <a href="#" onclick="openRegisterModal(); return false;" class="landing-btn landing-btn-white">
                        Daftar Sekarang - Gratis <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="footer-content">
            <div class="footer-logo-section">
                <div class="landing-logo" style="justify-content: center; margin-bottom: 1.5rem; color: white;">
                    <div class="landing-logo-icon"><i class="fas fa-recycle"></i></div>
                    CleanSpot
                </div>
                <p style="color: rgba(255, 255, 255, 0.8); max-width: 550px; margin: 0 auto; line-height: 1.7;">
                    Platform pelaporan infrastruktur terpadu yang menghubungkan warga, 
                    petugas lapangan, dan pemerintah daerah untuk menciptakan kota yang lebih baik, 
                    bersih, dan nyaman untuk semua.
                </p>
            </div>
            <div class="footer-links">
                <a href="#who">Layanan Kami</a>
                <a href="#features">Fitur Unggulan</a>
                <a href="#" onclick="openLoginModal(); return false;">Masuk</a>
                <a href="#" onclick="openRegisterModal(); return false;">Daftar Sekarang</a>
            </div>
            <p class="footer-text">© 2025 CleanSpot - Kelompok 33. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeLoginModal()">×</button>
            
            <!-- Left Side Illustration -->
            <div class="modal-side">
                <div class="modal-side-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h3>Selamat Datang!</h3>
                <p>Masuk ke akun Anda untuk melaporkan dan memantau masalah infrastruktur di kota Anda.</p>
            </div>
            
            <!-- Right Side Form -->
            <div class="modal-form-side">
                <h2 class="modal-title">Masuk ke CleanSpot</h2>
                <p class="modal-subtitle">Masukkan kredensial Anda untuk melanjutkan</p>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="nama@email.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
                    </div>
                    
                    <div id="loginError" style="color: #ef4444; font-size: 0.875rem; margin-bottom: 1rem; display: none;"></div>
                    
                    <button type="submit" class="form-submit">
                        Masuk Sekarang <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                    </button>
                </form>
                
                <div class="form-footer">
                    Belum punya akun? <span class="form-link" onclick="switchToRegister()">Daftar sekarang</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="registerModal" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeRegisterModal()">×</button>
            
            <!-- Left Side Illustration -->
            <div class="modal-side">
                <div class="modal-side-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Bergabung Dengan Kami!</h3>
                <p>Daftar sekarang dan mulai berkontribusi untuk membuat kota kita lebih bersih dan nyaman.</p>
            </div>
            
            <!-- Right Side Form -->
            <div class="modal-form-side">
                <h2 class="modal-title">Buat Akun Baru</h2>
                <p class="modal-subtitle">Isi form di bawah untuk mendaftar</p>
                
                <form id="registerForm">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-input" placeholder="Nama lengkap Anda" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="nama@email.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" name="no_telp" class="form-input" placeholder="08xxxxxxxxxx" required>
                    </div>
                    
                    <div id="registerError" style="color: #ef4444; font-size: 0.875rem; margin-bottom: 1rem; display: none;"></div>
                    <div id="registerSuccess" style="color: #10b981; font-size: 0.875rem; margin-bottom: 1rem; display: none;"></div>
                    
                    <button type="submit" class="form-submit">
                        Daftar Sekarang <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                    </button>
                </form>
                
                <div class="form-footer">
                    Sudah punya akun? <span class="form-link" onclick="switchToLogin()">Masuk di sini</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Login Form Handler
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {
                email: formData.get('email'),
                password: formData.get('password')
            };
            
            try {
                const response = await fetch('auth/login_session.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.data.redirect;
                } else {
                    document.getElementById('loginError').textContent = result.message || 'Login gagal';
                    document.getElementById('loginError').style.display = 'block';
                }
            } catch (error) {
                document.getElementById('loginError').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                document.getElementById('loginError').style.display = 'block';
            }
        });
        
        // Register Form Handler
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {
                nama: formData.get('nama'),
                email: formData.get('email'),
                password: formData.get('password'),
                no_telp: formData.get('no_telp')
            };
            
            try {
                const response = await fetch('auth/register_session.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('registerSuccess').textContent = result.message || 'Registrasi berhasil! Silakan login.';
                    document.getElementById('registerSuccess').style.display = 'block';
                    document.getElementById('registerError').style.display = 'none';
                    setTimeout(() => switchToLogin(), 1500);
                } else {
                    document.getElementById('registerError').textContent = result.message || 'Registrasi gagal';
                    document.getElementById('registerError').style.display = 'block';
                    document.getElementById('registerSuccess').style.display = 'none';
                }
            } catch (error) {
                document.getElementById('registerError').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                document.getElementById('registerError').style.display = 'block';
                document.getElementById('registerSuccess').style.display = 'none';
            }
        });
        
        // Modal Functions
        function openLoginModal() {
            document.getElementById('loginModal').classList.add('active');
            document.body.style.overflow = 'hidden';
            document.getElementById('loginError').style.display = 'none';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('loginError').style.display = 'none';
        }

        function openRegisterModal() {
            document.getElementById('registerModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function switchToRegister() {
            closeLoginModal();
            setTimeout(() => openRegisterModal(), 200);
        }

        function switchToLogin() {
            closeRegisterModal();
            setTimeout(() => openLoginModal(), 200);
        }

        // Close modal when clicking outside
        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this) closeLoginModal();
        });

        document.getElementById('registerModal').addEventListener('click', function(e) {
            if (e.target === this) closeRegisterModal();
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe sections for animation
        document.querySelectorAll('.who-item, .feature-item, .who-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    </script>
</body>
</html>
