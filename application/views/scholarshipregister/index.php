<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($selected_exam['title']) ? htmlspecialchars($selected_exam['title']) . ' - SSAT 2026' : 'Sunrise Scholarship Cum Admission Test 2026'; ?></title>
    <meta name="description" content="Win up to 100% Scholarship with Sunrise SSAT 2026. Cash Awards, Free Registration, NCERT based exam for Classes 5th to 11th.">
    
    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('backend/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --pw-primary: #5A4BDA;
            --pw-primary-dark: #4739C2;
            --pw-primary-light: #F0EDFF;
            --pw-accent: #FF5A5F;
            --pw-dark: #1B2124;
            --pw-dark-2: #2B3338;
            --pw-gray-bg: #F8F9FC;
            --pw-gray-border: #E6E9F0;
            --pw-text: #1B2124;
            --pw-text-muted: #5A6A72;
            --pw-green: #00A86B;
            --pw-green-light: #E8F8F2;
            --pw-gold: #FFB800;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #FFFFFF;
            color: var(--pw-text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            padding-bottom: 70px;
            -webkit-font-smoothing: antialiased;
        }

        @media (min-width: 769px) {
            body { padding-bottom: 0; }
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--pw-dark);
        }

        /* Top Announcement Strip */
        .top-banner-strip {
            background: linear-gradient(90deg, #1B2124 0%, #2A215C 100%);
            color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            padding: 10px 16px;
            text-align: center;
        }
        .top-banner-strip .tag-pill {
            background: var(--pw-accent);
            color: #ffffff;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 20px;
            margin-right: 8px;
            display: inline-block;
        }

        /* Modern EdTech Sticky Navigation */
        .pw-navbar {
            background: #ffffff;
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 14px 0;
            border-bottom: 1px solid var(--pw-gray-border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }
        .pw-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .pw-brand:hover, .pw-brand:focus { text-decoration: none; }
        .pw-logo-badge {
            width: 44px;
            height: 44px;
            background: var(--pw-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 22px;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(90, 75, 218, 0.3);
        }
        .pw-brand-title {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--pw-dark);
            margin: 0;
            line-height: 1.1;
        }
        .pw-brand-sub {
            font-size: 11px;
            color: var(--pw-primary);
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .pw-nav-menu {
            display: flex;
            align-items: center;
            gap: 24px;
            justify-content: flex-end;
        }
        .pw-nav-menu a {
            color: var(--pw-dark-2);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.2s;
        }
        .pw-nav-menu a:hover {
            color: var(--pw-primary);
        }
        .btn-pw-nav {
            background: var(--pw-primary);
            color: #ffffff !important;
            padding: 9px 20px;
            border-radius: 8px;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(90, 75, 218, 0.25);
            transition: all 0.2s;
        }
        .btn-pw-nav:hover {
            background: var(--pw-primary-dark);
            transform: translateY(-1px);
        }

        /* Language Toggle Pill */
        .lang-switch {
            display: inline-flex;
            background: var(--pw-gray-bg);
            border: 1px solid var(--pw-gray-border);
            padding: 3px;
            border-radius: 20px;
        }
        .lang-switch-btn {
            border: none;
            background: transparent;
            padding: 3px 12px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 16px;
            cursor: pointer;
            color: var(--pw-text-muted);
            transition: all 0.2s;
        }
        .lang-switch-btn.active {
            background: var(--pw-primary);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(90, 75, 218, 0.3);
        }

        /* HERO SECTION (Physics Wallah NSAT Style) */
        .pw-hero-section {
            background: radial-gradient(100% 100% at 50% 0%, #F5F3FF 0%, #FFFFFF 100%);
            padding: 50px 0 60px;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--pw-gray-border);
        }
        .hero-pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1.5px solid var(--pw-primary-light);
            box-shadow: 0 2px 8px rgba(90, 75, 218, 0.08);
            border-radius: 30px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 700;
            color: var(--pw-primary);
            margin-bottom: 20px;
        }
        .hero-main-title {
            font-size: 46px;
            line-height: 1.15;
            font-weight: 900;
            color: var(--pw-dark);
            margin: 0 0 16px 0;
        }
        .hero-highlight-text {
            color: var(--pw-primary);
            background: linear-gradient(135deg, #5A4BDA 0%, #8065FF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-lead-text {
            font-size: 17px;
            color: var(--pw-text-muted);
            margin-bottom: 28px;
            max-width: 560px;
            line-height: 1.6;
        }
        
        /* Quick Value USPs (Grid of 4 micro-badges) */
        .hero-usp-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 30px;
        }
        .usp-box {
            background: #ffffff;
            border: 1px solid var(--pw-gray-border);
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .usp-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .usp-icon.purple { background: var(--pw-primary-light); color: var(--pw-primary); }
        .usp-icon.green { background: var(--pw-green-light); color: var(--pw-green); }
        .usp-icon.orange { background: #FFF4E5; color: #FF9800; }
        .usp-icon.blue { background: #EBF5FF; color: #0088FF; }

        .usp-text strong {
            display: block;
            font-size: 14px;
            color: var(--pw-dark);
            font-weight: 700;
            line-height: 1.2;
        }
        .usp-text span {
            font-size: 12px;
            color: var(--pw-text-muted);
        }

        /* Hero Action Buttons */
        .hero-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .btn-register-hero {
            background: var(--pw-primary);
            color: #ffffff;
            font-size: 16px;
            font-weight: 800;
            padding: 14px 32px;
            border-radius: 10px;
            border: none;
            box-shadow: 0 6px 20px rgba(90, 75, 218, 0.35);
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .btn-register-hero:hover {
            color: #ffffff;
            background: var(--pw-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(90, 75, 218, 0.45);
            text-decoration: none;
        }
        .btn-brochure-hero {
            background: #ffffff;
            color: var(--pw-dark);
            font-size: 15px;
            font-weight: 700;
            padding: 13px 24px;
            border-radius: 10px;
            border: 1.5px solid var(--pw-gray-border);
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-brochure-hero:hover {
            border-color: var(--pw-primary);
            color: var(--pw-primary);
            text-decoration: none;
        }

        /* Hero Right: Live Test Info Card */
        .hero-exam-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 16px 40px rgba(27, 33, 36, 0.08);
            border: 1px solid var(--pw-gray-border);
            padding: 30px;
            position: relative;
        }
        .hero-exam-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--pw-primary) 0%, var(--pw-accent) 100%);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
        .card-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--pw-gray-border);
        }
        .card-header-flex h3 {
            font-size: 20px;
            margin: 0;
            font-weight: 800;
        }
        .verified-badge {
            background: var(--pw-green-light);
            color: var(--pw-green);
            font-size: 12px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .exam-info-row {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px dashed var(--pw-gray-border);
        }
        .exam-info-row:last-child { border-bottom: none; }
        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--pw-gray-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pw-primary);
            font-size: 18px;
            flex-shrink: 0;
        }
        .info-content .label-text {
            font-size: 12px;
            color: var(--pw-text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-content .value-text {
            font-size: 15px;
            font-weight: 700;
            color: var(--pw-dark);
            margin-top: 2px;
        }

        /* Countdown Strip Inside Card */
        .card-countdown {
            background: var(--pw-gray-bg);
            border-radius: 14px;
            padding: 16px;
            text-align: center;
            margin-top: 20px;
        }
        .card-countdown-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--pw-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
        }
        .card-countdown-boxes {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .c-box {
            background: #ffffff;
            border: 1px solid var(--pw-gray-border);
            border-radius: 8px;
            padding: 8px 12px;
            min-width: 58px;
        }
        .c-box strong {
            display: block;
            font-size: 20px;
            font-weight: 900;
            color: var(--pw-primary);
            font-family: 'Outfit', sans-serif;
            line-height: 1;
        }
        .c-box span {
            font-size: 10px;
            color: var(--pw-text-muted);
            text-transform: uppercase;
            font-weight: 600;
        }

        /* STATS / IMPACT COUNTER SECTION (PW Vidyapeeth Style) */
        .pw-impact-section {
            background: #ffffff;
            padding: 40px 0;
            border-bottom: 1px solid var(--pw-gray-border);
        }
        .impact-card {
            text-align: center;
            padding: 15px;
        }
        .impact-number {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 900;
            color: var(--pw-primary);
            line-height: 1;
            margin-bottom: 6px;
        }
        .impact-label {
            font-size: 14px;
            color: var(--pw-dark-2);
            font-weight: 700;
        }
        .impact-sub {
            font-size: 12px;
            color: var(--pw-text-muted);
        }

        /* SECTION STYLING */
        .pw-section {
            padding: 70px 0;
        }
        .pw-section.gray-bg {
            background-color: var(--pw-gray-bg);
        }
        .pw-section-header {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 50px;
        }
        .pw-badge-pill {
            background: var(--pw-primary-light);
            color: var(--pw-primary);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 14px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .pw-section-title {
            font-size: 36px;
            font-weight: 800;
            margin: 0 0 12px;
        }
        .pw-section-sub {
            font-size: 16px;
            color: var(--pw-text-muted);
            margin: 0;
        }

        /* STEPS TO GET SCHOLARSHIP (PW 3-STEP FLOW) */
        .step-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px 24px;
            border: 1px solid var(--pw-gray-border);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.03);
            text-align: center;
            position: relative;
            height: 100%;
            transition: transform 0.2s ease;
        }
        .step-card:hover {
            transform: translateY(-4px);
        }
        .step-number {
            width: 48px;
            height: 48px;
            background: var(--pw-primary);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 900;
            margin: 0 auto 18px;
            font-family: 'Outfit', sans-serif;
            box-shadow: 0 4px 12px rgba(90, 75, 218, 0.3);
        }
        .step-card h4 {
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 10px;
        }
        .step-card p {
            font-size: 14px;
            color: var(--pw-text-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* REWARDS & SCHOLARSHIPS CARDS */
        .reward-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--pw-gray-border);
            padding: 32px 24px;
            text-align: center;
            height: 100%;
            transition: all 0.25s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
        }
        .reward-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.07);
            border-color: var(--pw-primary);
        }
        .reward-top-tag {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--pw-gold);
            color: #000;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 4px 12px;
            border-bottom-left-radius: 10px;
        }
        .reward-icon-circle {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .reward-icon-circle.gold { background: #FFF9E6; color: var(--pw-gold); }
        .reward-icon-circle.purple { background: var(--pw-primary-light); color: var(--pw-primary); }
        .reward-icon-circle.green { background: var(--pw-green-light); color: var(--pw-green); }
        
        .reward-card h4 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .reward-card p {
            color: var(--pw-text-muted);
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        /* SYLLABUS & BLUEPRINT TABLE */
        .pw-table-container {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--pw-gray-border);
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0,0,0,0.03);
        }
        .table-pw {
            margin-bottom: 0;
        }
        .table-pw thead th {
            background: var(--pw-dark);
            color: #ffffff;
            font-weight: 700;
            font-size: 13.5px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 16px 20px;
            border: none;
        }
        .table-pw tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-top: 1px solid var(--pw-gray-border);
            font-size: 14px;
        }
        .class-pill {
            background: var(--pw-primary-light);
            color: var(--pw-primary);
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
        }

        /* REGISTRATION FORM CARD (PW Vidyapeeth Form Style) */
        .pw-form-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid var(--pw-gray-border);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.06);
            padding: 40px;
            margin-top: -10px;
        }
        .form-control-pw {
            height: 48px;
            border-radius: 10px;
            border: 1.5px solid var(--pw-gray-border);
            font-size: 14.5px;
            padding: 10px 16px;
            box-shadow: none;
            transition: all 0.2s;
        }
        .form-control-pw:focus {
            border-color: var(--pw-primary);
            box-shadow: 0 0 0 4px rgba(90, 75, 218, 0.12);
        }
        .form-label-pw {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--pw-dark-2);
            margin-bottom: 8px;
            display: block;
        }
        .btn-submit-pw {
            background: var(--pw-primary);
            color: #ffffff;
            font-size: 17px;
            font-weight: 800;
            padding: 15px 30px;
            border-radius: 12px;
            border: none;
            width: 100%;
            box-shadow: 0 8px 24px rgba(90, 75, 218, 0.35);
            transition: all 0.25s;
            cursor: pointer;
        }
        .btn-submit-pw:hover {
            background: var(--pw-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(90, 75, 218, 0.45);
        }

        /* DATES TIMELINE CARDS */
        .date-box {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid var(--pw-gray-border);
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .date-calendar-badge {
            background: var(--pw-primary-light);
            border-radius: 10px;
            width: 52px;
            height: 52px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--pw-primary);
            font-weight: 800;
        }
        .date-calendar-badge strong { font-size: 18px; line-height: 1; }
        .date-calendar-badge span { font-size: 10px; text-transform: uppercase; font-weight: 700; }

        /* FAQs ACCORDION */
        .faq-item-pw {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--pw-gray-border);
            margin-bottom: 14px;
            overflow: hidden;
        }
        .faq-q {
            padding: 18px 22px;
            font-size: 16px;
            font-weight: 700;
            color: var(--pw-dark);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            user-select: none;
        }
        .faq-a {
            padding: 0 22px 18px;
            font-size: 14.5px;
            color: var(--pw-text-muted);
            line-height: 1.6;
            display: none;
        }
        .faq-item-pw.open .faq-a {
            display: block;
        }
        .faq-item-pw.open .faq-q i {
            transform: rotate(180deg);
        }

        /* MOBILE FLOATING CTA */
        .pw-mobile-cta {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid var(--pw-gray-border);
            padding: 10px 15px;
            z-index: 9999;
            box-shadow: 0 -4px 15px rgba(0,0,0,0.08);
            display: none;
        }
        @media (max-width: 768px) {
            .pw-mobile-cta { display: block; }
            .hero-main-title { font-size: 32px; }
            .hero-usp-grid { grid-template-columns: 1fr; }
            .pw-form-card { padding: 24px 16px; }
        }

        /* PW NSAT REGISTRATION POPUP MODAL (Exact match to PW NSAT) */
        .pw-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 999999;
            display: none;
            padding: 20px 16px;
            overflow-y: auto;
            opacity: 0;
            transition: opacity 0.22s ease-in-out;
        }
        .pw-modal-overlay.active {
            display: flex !important;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }
        .pw-modal-card {
            background: #FFFFFF;
            border-radius: 16px;
            max-width: 480px;
            width: 100%;
            margin: auto;
            padding: 28px 26px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.35);
            position: relative;
            transform: translateY(15px) scale(0.98);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            max-height: 92vh;
            overflow-y: auto;
        }
        .pw-modal-overlay.active .pw-modal-card {
            transform: translateY(0) scale(1);
        }
        .pw-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .pw-modal-title {
            font-size: 22px;
            font-weight: 800;
            color: #1B2124;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.01em;
        }
        .pw-modal-close {
            background: transparent;
            border: none;
            font-size: 26px;
            color: #64748B;
            line-height: 1;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
            padding: 0;
        }
        .pw-modal-close:hover {
            background: #F1F5F9;
            color: #0F172A;
        }
        .pw-modal-input {
            width: 100%;
            height: 52px;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            padding: 12px 18px;
            font-size: 16px;
            font-family: inherit;
            color: #0F172A;
            outline: none;
            transition: all 0.2s ease;
            background: #FFFFFF;
        }
        .pw-modal-input:focus {
            border-color: var(--pw-primary);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.15);
        }
        .pw-modal-btn-disabled {
            width: 100%;
            height: 50px;
            background: #94A3B8;
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: not-allowed;
            transition: all 0.2s ease;
        }
        .pw-modal-btn-active {
            width: 100%;
            height: 50px;
            background: var(--pw-primary);
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(90, 75, 218, 0.3);
            transition: all 0.2s ease;
        }
        .pw-modal-btn-active:hover {
            background: var(--pw-primary-dark);
            transform: translateY(-1px);
        }
        .pw-modal-pin-input {
            width: 100%;
            height: 48px;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            font-size: 22px;
            letter-spacing: 10px;
            text-align: center;
            font-weight: 800;
            background: #FFFFFF;
            outline: none;
            transition: all 0.2s;
        }
        .pw-modal-pin-input:focus {
            border-color: var(--pw-primary);
            box-shadow: 0 0 0 3px rgba(90, 75, 218, 0.15);
        }
        .candidate-select-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 8px;
            cursor: pointer;
            background: #FFFFFF;
            transition: all 0.2s;
        }
        .candidate-select-card:hover,
        .candidate-select-card.active {
            border-color: var(--pw-primary);
            background: var(--pw-primary-light);
        }
    </style>
</head>
<body>

<!-- 1. Top Announcement Strip -->
<div class="top-banner-strip">
    <span class="tag-pill">NATIONAL SCHOLARSHIP</span>
    <strong>Sunrise Scholarship Cum Admission Test (SSAT-2026)</strong> &bull; Registrations Open &bull; 100% Free Entry
    <a href="#register" style="color: #FFB800; font-weight: 700; margin-left: 10px; text-decoration: underline;">Register Online &rarr;</a>
</div>

<!-- 2. Sticky Header Navbar -->
<header class="pw-navbar">
    <div class="container">
        <div class="row" style="display: flex; align-items: center;">
            <div class="col-xs-8 col-sm-4">
                <a href="<?php echo base_url(); ?>" class="pw-brand">
                    <div class="pw-logo-badge">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h4 class="pw-brand-title"><?php echo htmlspecialchars($sch_setting[0]['name'] ?: 'Sunrise International'); ?></h4>
                        <span class="pw-brand-sub">Scholarship & Admission Wing</span>
                    </div>
                </a>
            </div>

            <div class="col-xs-4 col-sm-8 text-right">
                <div class="pw-nav-menu hidden-xs">
                    <a href="#about">About</a>
                    <a href="#benefits">Rewards</a>
                    <a href="#steps">How It Works</a>
                    <a href="#syllabus">Syllabus</a>
                    <a href="#dates">Dates</a>
                    <a href="<?php echo site_url('scholarshipregister/portal'); ?>" style="color: var(--pw-primary); font-weight: 700;">
                        <i class="fa fa-user-circle"></i> Candidate Portal
                    </a>
                    
                    <!-- Language Switcher -->
                    <div class="lang-switch">
                        <button type="button" class="lang-switch-btn active" id="btn_lang_en" onclick="setLanguage('en')">EN</button>
                        <button type="button" class="lang-switch-btn" id="btn_lang_hi" onclick="setLanguage('hi')">हिन्दी</button>
                    </div>

                    <a href="#register" class="btn-pw-nav">Register (Free)</a>
                </div>

                <!-- Mobile Action -->
                <div class="visible-xs" style="display: inline-flex; align-items: center; gap: 8px;">
                    <div class="lang-switch">
                        <button type="button" class="lang-switch-btn active" id="m_btn_lang_en" onclick="setLanguage('en')" style="padding: 2px 7px;">EN</button>
                        <button type="button" class="lang-switch-btn" id="m_btn_lang_hi" onclick="setLanguage('hi')" style="padding: 2px 7px;">हि</button>
                    </div>
                    <a href="#register" class="btn btn-xs btn-primary" style="background: var(--pw-primary); border-radius: 6px; font-weight: 700; padding: 6px 12px;">Apply</a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- 3. HERO SECTION (PW NSAT Banner + Exam Summary Box) -->
<section id="about" class="pw-hero-section">
    <div class="container">
        <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
            
            <!-- Left: Headline & USPs -->
            <div class="col-md-7 col-sm-12">
                <div class="hero-pill-badge">
                    <i class="fa fa-bolt" style="color: var(--pw-gold);"></i> 
                    Classes 5th to 11th &bull; Academic Session 2026-27
                </div>

                <h1 class="hero-main-title">
                    Win up to <span class="hero-highlight-text">100% Scholarship</span> with SSAT 2026
                </h1>

                <p class="hero-lead-text">
                    Unlock premier quality school & foundation education. Benchmark yourself with thousands of students and secure grand fee waivers, cash honors and direct admission.
                </p>

                <!-- 4 Value USPs (PW Style) -->
                <div class="hero-usp-grid">
                    <div class="usp-box">
                        <div class="usp-icon purple"><i class="fa fa-gift"></i></div>
                        <div class="usp-text">
                            <strong>100% Fee Waiver</strong>
                            <span>Top rankers get complete tuition fee off</span>
                        </div>
                    </div>
                    <div class="usp-box">
                        <div class="usp-icon green"><i class="fa fa-inr"></i></div>
                        <div class="usp-text">
                            <strong>Merit Cash Honors</strong>
                            <span>Cash prizes, mementos & medals</span>
                        </div>
                    </div>
                    <div class="usp-box">
                        <div class="usp-icon orange"><i class="fa fa-file-text-o"></i></div>
                        <div class="usp-text">
                            <strong>100% NCERT Pattern</strong>
                            <span>Bilingual OMR MCQ question paper</span>
                        </div>
                    </div>
                    <div class="usp-box">
                        <div class="usp-icon blue"><i class="fa fa-check-circle"></i></div>
                        <div class="usp-text">
                            <strong>100% Free Registration</strong>
                            <span>No application or exam fee required</span>
                        </div>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="#register" class="btn-register-hero">
                        <i class="fa fa-pencil-square-o"></i> Register Online (Free)
                    </a>
                    <a href="<?php echo site_url('admin/scholarshipexam/brochure/' . (isset($selected_exam['id']) ? $selected_exam['id'] : 1)); ?>" target="_blank" class="btn-brochure-hero">
                        <i class="fa fa-download"></i> Download Brochure
                    </a>
                </div>
            </div>

            <!-- Right: Examination Details Box (PW Vidyapeeth Style) -->
            <div class="col-md-5 col-sm-12" style="margin-top: 25px;">
                <div class="hero-exam-card">
                    <div class="card-header-flex">
                        <h3>Exam Details</h3>
                        <span class="verified-badge"><i class="fa fa-check"></i> OFFICIAL</span>
                    </div>

                    <div class="exam-info-row">
                        <div class="info-icon"><i class="fa fa-calendar"></i></div>
                        <div class="info-content">
                            <div class="label-text">Date of Examination</div>
                            <div class="value-text">15 November 2026 (Sunday)</div>
                        </div>
                    </div>

                    <div class="exam-info-row">
                        <div class="info-icon"><i class="fa fa-clock-o"></i></div>
                        <div class="info-content">
                            <div class="label-text">Format & Duration</div>
                            <div class="value-text">90 Minutes &bull; OMR Multiple Choice</div>
                        </div>
                    </div>

                    <div class="exam-info-row">
                        <div class="info-icon"><i class="fa fa-graduation-cap"></i></div>
                        <div class="info-content">
                            <div class="label-text">Eligibility Criteria</div>
                            <div class="value-text">Classes 5th to 11th (Any recognized school)</div>
                        </div>
                    </div>

                    <div class="exam-info-row">
                        <div class="info-icon"><i class="fa fa-trophy"></i></div>
                        <div class="info-content">
                            <div class="label-text">Scholarship Concession</div>
                            <div class="value-text">Up to 100% Fee Concession + Cash Honors</div>
                        </div>
                    </div>

                    <!-- Countdown Inside Card -->
                    <div class="card-countdown">
                        <div class="card-countdown-title"><i class="fa fa-hourglass-half"></i> Registration Closing Soon</div>
                        <div class="card-countdown-boxes">
                            <div class="c-box"><strong id="cd_days">03</strong><span>Days</span></div>
                            <div class="c-box"><strong id="cd_hours">19</strong><span>Hours</span></div>
                            <div class="c-box"><strong id="cd_mins">50</strong><span>Mins</span></div>
                            <div class="c-box"><strong id="cd_secs">20</strong><span>Secs</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 4. IMPACT COUNTER NUMBERS (PW NSAT Trust Markers) -->
<section class="pw-impact-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-6 impact-card">
                <div class="impact-number">100%</div>
                <div class="impact-label">Tuition Fee Waiver</div>
                <div class="impact-sub">For top meritorious students</div>
            </div>
            <div class="col-md-3 col-xs-6 impact-card">
                <div class="impact-number">Classes 5–11</div>
                <div class="impact-label">Eligible Grades</div>
                <div class="impact-sub">From all state and national boards</div>
            </div>
            <div class="col-md-3 col-xs-6 impact-card">
                <div class="impact-number">₹ Cash Honors</div>
                <div class="impact-label">Cash Rewards & Trophies</div>
                <div class="impact-sub">Direct cash awards for rankers</div>
            </div>
            <div class="col-md-3 col-xs-6 impact-card">
                <div class="impact-number">FREE</div>
                <div class="impact-label">Registration Cost</div>
                <div class="impact-sub">Zero exam fees for all applicants</div>
            </div>
        </div>
    </div>
</section>

<!-- 5. THREE EASY STEPS TO GET SCHOLARSHIP (PW NSAT Step Flow) -->
<section id="steps" class="pw-section gray-bg">
    <div class="container">
        <div class="pw-section-header">
            <span class="pw-badge-pill">Simple Process</span>
            <h2 class="pw-section-title">Steps To Get Scholarship</h2>
            <p class="pw-section-sub">Follow three quick steps to participate in SSAT 2026 and claim your scholarship.</p>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h4>Register Online for Free</h4>
                    <p>Fill out the simple application form below with your class & mobile number. Set your 4-digit security PIN.</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h4>Download Model Paper & Prepare</h4>
                    <p>Log in to your Candidate Portal using your Roll No and PIN to practice with authentic class-wise bilingual sample papers.</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h4>Appear for Exam & Win</h4>
                    <p>Attend the OMR test on 15 Nov 2026. Secure your All India Rank, cash reward, and up to 100% school fee concession.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. SCHOLARSHIPS & REWARDS SECTION (PW Vidyapeeth Style) -->
<section id="benefits" class="pw-section">
    <div class="container">
        <div class="pw-section-header">
            <span class="pw-badge-pill">Rewards &amp; Honors</span>
            <h2 class="pw-section-title">What You Win with SSAT 2026</h2>
            <p class="pw-section-sub">Empowering talented students with financial assistance and prestigious accolades.</p>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="reward-card">
                    <span class="reward-top-tag">Top Privilege</span>
                    <div class="reward-icon-circle purple"><i class="fa fa-graduation-cap"></i></div>
                    <h4>Up to 100% Fee Waiver</h4>
                    <p>Ranks 1 to 10 in each class get up to 100% complete waiver on tuition fees for the entire academic session 2026-27.</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="reward-card">
                    <span class="reward-top-tag">Cash Honors</span>
                    <div class="reward-icon-circle gold"><i class="fa fa-trophy"></i></div>
                    <h4>Cash Awards & Mementos</h4>
                    <p>Substantial cash rewards, personalized trophies, certificates of excellence, and medals awarded in a grand ceremony.</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="reward-card">
                    <span class="reward-top-tag">Guaranteed</span>
                    <div class="reward-icon-circle green"><i class="fa fa-id-badge"></i></div>
                    <h4>Priority Direct Admission</h4>
                    <p>Merit holders receive assured direct admission into Foundation, Sainik/Military, and Pre-Medical/Pre-Engineering wings.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. SYLLABUS & BLUEPRINT TABLE -->
<section id="syllabus" class="pw-section gray-bg">
    <div class="container">
        <div class="pw-section-header">
            <span class="pw-badge-pill">Examination Pattern</span>
            <h2 class="pw-section-title">Syllabus &amp; Question Blueprint</h2>
            <p class="pw-section-sub">Prepared strictly according to 100% NCERT textbook curriculum. All questions are bilingual (Hindi &amp; English).</p>
        </div>

        <div class="pw-table-container">
            <div class="table-responsive">
                <table class="table table-pw table-hover">
                    <thead>
                        <tr>
                            <th>Class Group</th>
                            <th>Subjects Covered</th>
                            <th class="text-center">Total Questions</th>
                            <th class="text-center">Maximum Marks</th>
                            <th class="text-center">Exam Mode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="class-pill">Class 5th</span></td>
                            <td><strong>Hindi, English, Mathematics, EVS</strong></td>
                            <td class="text-center"><strong>40</strong></td>
                            <td class="text-center"><span class="label label-success" style="font-size: 13px; font-weight: 700; background: var(--pw-green);">50 Marks</span></td>
                            <td class="text-center"><span class="label label-default">Offline OMR</span></td>
                        </tr>
                        <tr>
                            <td><span class="class-pill">Class 6th to 10th</span></td>
                            <td><strong>Hindi, English, Science, Mathematics, Social Studies, Logical Reasoning</strong></td>
                            <td class="text-center"><strong>60</strong></td>
                            <td class="text-center"><span class="label label-success" style="font-size: 13px; font-weight: 700; background: var(--pw-green);">90 Marks</span></td>
                            <td class="text-center"><span class="label label-default">Offline OMR</span></td>
                        </tr>
                        <tr>
                            <td><span class="class-pill">Class 11th Science</span></td>
                            <td><strong>English, Mathematics / Biology, Physics, Chemistry</strong></td>
                            <td class="text-center"><strong>60</strong></td>
                            <td class="text-center"><span class="label label-success" style="font-size: 13px; font-weight: 700; background: var(--pw-green);">120 Marks</span></td>
                            <td class="text-center"><span class="label label-default">Offline OMR</span></td>
                        </tr>
                        <tr>
                            <td><span class="class-pill">Class 11th Arts</span></td>
                            <td><strong>English, Political Science, History, Geography</strong></td>
                            <td class="text-center"><strong>60</strong></td>
                            <td class="text-center"><span class="label label-success" style="font-size: 13px; font-weight: 700; background: var(--pw-green);">120 Marks</span></td>
                            <td class="text-center"><span class="label label-default">Offline OMR</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- 8. IMPORTANT DATES SECTION -->
<section id="dates" class="pw-section">
    <div class="container">
        <div class="pw-section-header">
            <span class="pw-badge-pill">Key Milestones</span>
            <h2 class="pw-section-title">Important Examination Dates</h2>
            <p class="pw-section-sub">Mark these essential schedule dates on your calendar.</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="date-box">
                    <div class="date-calendar-badge"><strong>15</strong><span>OCT</span></div>
                    <div>
                        <h4 style="margin: 0 0 4px; font-size: 16px; font-weight: 800;">Registration Commencement</h4>
                        <span style="font-size: 13px; color: var(--pw-text-muted);">Online &amp; offline application window opens for all students</span>
                    </div>
                </div>
                <div class="date-box">
                    <div class="date-calendar-badge"><strong>10</strong><span>NOV</span></div>
                    <div>
                        <h4 style="margin: 0 0 4px; font-size: 16px; font-weight: 800;">Registration Closing Date</h4>
                        <span style="font-size: 13px; color: var(--pw-text-muted);">Last date to submit application forms without late penalty</span>
                    </div>
                </div>
                <div class="date-box">
                    <div class="date-calendar-badge"><strong>12</strong><span>NOV</span></div>
                    <div>
                        <h4 style="margin: 0 0 4px; font-size: 16px; font-weight: 800;">Admit Card Release Date</h4>
                        <span style="font-size: 13px; color: var(--pw-text-muted);">Admit card available for download via Candidate Portal &amp; SMS</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="date-box" style="border-left: 4px solid var(--pw-primary);">
                    <div class="date-calendar-badge" style="background: var(--pw-primary); color: #fff;"><strong>15</strong><span>NOV</span></div>
                    <div>
                        <h4 style="margin: 0 0 4px; font-size: 16px; font-weight: 800; color: var(--pw-primary);">Examination Date & Reporting</h4>
                        <span style="font-size: 13px; color: var(--pw-text-muted);">15 November 2026, 10:00 AM (Reporting 9:30 AM)</span>
                    </div>
                </div>
                <div class="date-box">
                    <div class="date-calendar-badge"><strong>21</strong><span>NOV</span></div>
                    <div>
                        <h4 style="margin: 0 0 4px; font-size: 16px; font-weight: 800;">Result Announcement</h4>
                        <span style="font-size: 13px; color: var(--pw-text-muted);">Merit rank list published on portal &amp; school notice boards</span>
                    </div>
                </div>
                <div class="date-box">
                    <div class="date-calendar-badge"><strong>05</strong><span>DEC</span></div>
                    <div>
                        <h4 style="margin: 0 0 4px; font-size: 16px; font-weight: 800;">Award & Felicitation Ceremony</h4>
                        <span style="font-size: 13px; color: var(--pw-text-muted);">Grand ceremony for cash prizes, mementos &amp; certificates</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. REGISTRATION FORM (PW Vidyapeeth Form Flow) -->
<section id="register" class="pw-section gray-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="pw-form-card">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <span class="pw-badge-pill" style="background: var(--pw-green-light); color: var(--pw-green);">100% Free Entry</span>
                        <h2 style="font-size: 32px; font-weight: 900; margin: 10px 0 6px;">Candidate Registration Form</h2>
                        <p style="color: var(--pw-text-muted); font-size: 14.5px; margin: 0;">Fill in your details below to register. Roll Number & Admit Card will be generated instantaneously.</p>
                    </div>

                    <?php if (isset($registration_closed) && $registration_closed) { ?>
                        <div class="alert alert-danger text-center" style="border-radius: 12px; padding: 25px;">
                            <h3 style="margin: 0; font-weight: 800;"><i class="fa fa-ban"></i> Registrations Closed</h3>
                            <p style="margin-top: 8px;">Online registration for <?php echo htmlspecialchars($selected_exam['title']); ?> has concluded.</p>
                        </div>
                    <?php } else { ?>

                        <div id="sibling_notice_box" style="display: none; margin-bottom: 22px;"></div>

                        <form id="scholarship_register_form" action="<?php echo site_url('scholarshipregister/submit'); ?>" method="POST">
                            <?php if (!empty($selected_exam) && isset($is_direct_link) && $is_direct_link) { ?>
                                <input type="hidden" name="exam_id" value="<?php echo $selected_exam['id']; ?>">
                            <?php } else { ?>
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label class="form-label-pw">Target Examination *</label>
                                    <select name="exam_id" class="form-control form-control-pw" required>
                                        <?php if (!empty($exams)) {
                                            foreach ($exams as $e) { ?>
                                                <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($e['title']); ?> (<?php echo htmlspecialchars($e['exam_code']); ?>)
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="form-label-pw">Candidate Full Name *</label>
                                        <input type="text" id="form_candidate_name" name="candidate_name" class="form-control form-control-pw" placeholder="Enter student's full name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="form-label-pw">Present School Type *</label>
                                        <div style="display: flex; gap: 10px; margin-top: 4px;">
                                            <label style="flex: 1; border: 1.5px solid var(--pw-gray-border); padding: 10px 14px; border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                                                <input type="radio" name="school_type" value="internal" checked onclick="$('#other_school_box').hide();">
                                                <span>Sunrise Student</span>
                                            </label>
                                            <label style="flex: 1; border: 1.5px solid var(--pw-gray-border); padding: 10px 14px; border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                                                <input type="radio" name="school_type" value="external" onclick="$('#other_school_box').show();">
                                                <span>Other School</span>
                                            </label>
                                        </div>
                                        <div id="other_school_box" style="display: none; margin-top: 10px;">
                                            <input type="text" name="other_school_name" class="form-control form-control-pw" placeholder="Enter school name">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="form-label-pw">Primary Mobile Number (WhatsApp) *</label>
                                        <input type="tel" id="form_candidate_mobile" name="mobile" maxlength="10" pattern="\d{10}" class="form-control form-control-pw" placeholder="10-digit mobile number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="form-label-pw">Present Class / Grade *</label>
                                        <select id="form_class_id" name="class_id" class="form-control form-control-pw" required>
                                            <option value="">-- Select Class --</option>
                                            <?php foreach ($classList as $c) { ?>
                                                <option value="<?php echo $c['id']; ?>">Class <?php echo htmlspecialchars($c['class']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="form-label-pw">Father's Name</label>
                                        <input type="text" name="father_name" class="form-control form-control-pw" placeholder="Father's full name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="form-label-pw">Email Address</label>
                                        <input type="email" name="email" class="form-control form-control-pw" placeholder="candidate@example.com">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="form-label-pw">Gender</label>
                                        <select name="gender" class="form-control form-control-pw">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label class="form-label-pw">City / District</label>
                                        <input type="text" name="city" class="form-control form-control-pw" placeholder="City or District">
                                    </div>
                                </div>
                            </div>

                            <!-- 4-Digit Password PIN for Candidate Portal -->
                            <div style="background: var(--pw-primary-light); border: 1.5px solid #D5CFFE; border-radius: 14px; padding: 20px; margin: 15px 0 25px;">
                                <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
                                    <div class="col-sm-6">
                                        <label class="form-label-pw" style="color: var(--pw-primary); margin-bottom: 4px;">
                                            <i class="fa fa-lock"></i> Set 4-Digit Password PIN *
                                        </label>
                                        <input type="password" id="form_password_pin" name="password_pin" maxlength="4" pattern="\d{4}" class="form-control form-control-pw" placeholder="••••" style="letter-spacing: 6px; font-size: 20px; font-weight: 800; text-align: center; background: #fff;" required>
                                    </div>
                                    <div class="col-sm-6" style="padding-top: 8px;">
                                        <small style="color: var(--pw-dark-2); line-height: 1.5; display: block;">
                                            <i class="fa fa-info-circle text-primary"></i> <strong>Candidate Portal Access:</strong><br>
                                            Use your Roll Number and this 4-digit PIN to login to your dashboard to download class-wise sample papers and admit card.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit-pw">
                                <i class="fa fa-check-circle"></i> Submit Application &amp; Generate Roll Slip
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 10. FAQs SECTION (Accordion Style) -->
<section class="pw-section">
    <div class="container">
        <div class="pw-section-header">
            <span class="pw-badge-pill">Help &amp; Support</span>
            <h2 class="pw-section-title">Frequently Asked Questions</h2>
            <p class="pw-section-sub">Have questions about the scholarship test? Find your answers here.</p>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="faq-item-pw open">
                    <div class="faq-q" onclick="$(this).parent().toggleClass('open');">
                        <span>Who is eligible for the Sunrise Scholarship Test?</span>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        Students presently studying in Classes 5th to 11th in any recognized school (CBSE, RBSE, ICSE or State Boards) are eligible to participate. Both English and Hindi medium students are welcomed.
                    </div>
                </div>

                <div class="faq-item-pw">
                    <div class="faq-q" onclick="$(this).parent().toggleClass('open');">
                        <span>What is the syllabus and medium of examination?</span>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        The examination is conducted strictly as per NCERT textbook syllabus of the candidate's current class. The test paper is bilingual containing both Hindi and English for all questions.
                    </div>
                </div>

                <div class="faq-item-pw">
                    <div class="faq-q" onclick="$(this).parent().toggleClass('open');">
                        <span>Is there any registration fee?</span>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        Online registration for SSAT-2026 is completely <strong>FREE</strong> of cost! You can register online, via WhatsApp helpline (+91 9783200821), or in-person at the school reception.
                    </div>
                </div>

                <div class="faq-item-pw">
                    <div class="faq-q" onclick="$(this).parent().toggleClass('open');">
                        <span>How will the scholarship awards be distributed?</span>
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        Top meritorious rankers will receive up to 100% concession on tuition fees for the 2026-27 session, along with cash awards, mementos, medals, and official certificates of excellence.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 11. Footer -->
<footer style="background: var(--pw-dark); color: #94A3B8; padding: 50px 0 30px;">
    <div class="container text-center">
        <h3 style="color: #fff; font-family: 'Outfit', sans-serif; font-size: 24px; margin-bottom: 8px;">
            <?php echo htmlspecialchars($sch_setting[0]['name'] ?: 'Sunrise Education Group'); ?>
        </h3>
        <p style="margin-bottom: 25px; color: #94A3B8;">Affiliated with CBSE &bull; School &bull; Sainik-Military &bull; Pre-Foundation &bull; College</p>
        
        <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin-bottom: 30px; font-size: 14px;">
            <span><i class="fa fa-phone" style="color: var(--pw-primary);"></i> Helpline: +91 9828512821 / 9783200821</span>
            <span><i class="fa fa-envelope" style="color: var(--pw-accent);"></i> info@sunriseschool.in</span>
            <span><i class="fa fa-map-marker" style="color: var(--pw-green);"></i> Nechhwa, Sikar (Raj.)</span>
        </div>

        <div style="border-top: 1px solid #2B3338; padding-top: 25px; font-size: 13px;">
            &copy; 2026 <?php echo htmlspecialchars($sch_setting[0]['name'] ?: 'Sunrise International Public School'); ?>. All Rights Reserved.
        </div>
    </div>
</footer>

<!-- Floating Mobile Bottom CTA -->
<div class="pw-mobile-cta">
    <div style="display: flex; gap: 10px;">
        <a href="#register" class="btn btn-primary" style="flex: 2; background: var(--pw-primary); border-radius: 8px; font-weight: 800; padding: 10px;">
            <i class="fa fa-pencil"></i> Apply Now (Free)
        </a>
        <a href="https://wa.me/919783200821" target="_blank" class="btn btn-success" style="flex: 1; background: #25D366; border-radius: 8px; font-weight: 800; padding: 10px;">
            <i class="fa fa-whatsapp"></i> Chat
        </a>
    </div>
</div>

<!-- PW NSAT-Style Mobile Registration Popup Modal -->
<div id="pwRegisterModal" class="pw-modal-overlay">
    <div class="pw-modal-card">
        <!-- Close Button -->
        <button type="button" class="pw-modal-close" onclick="closeRegisterModal();" aria-label="Close">&times;</button>
        
        <!-- STEP 1: MOBILE ENTRY SCREEN (Exact layout from PW NSAT screenshot) -->
        <div id="modalStepMobile">
            <h3 class="pw-modal-title">Register for Scholarship</h3>
            <div style="height: 18px;"></div>
            
            <div style="position: relative; margin-bottom: 20px;">
                <input type="tel" 
                       id="modal_mobile_input" 
                       placeholder="Mobile Number" 
                       maxlength="10" 
                       autocomplete="tel"
                       class="pw-modal-input">
                <div id="modal_mobile_error" style="display: none; color: #EF4444; font-size: 13px; margin-top: 6px; font-weight: 600;"></div>
            </div>

            <button type="button" 
                    id="modal_verify_btn" 
                    class="pw-modal-btn-disabled" 
                    disabled 
                    onclick="handleVerifyMobile();">
                Verify &amp; Proceed
            </button>

            <div style="text-align: center; margin-top: 18px; font-size: 13.5px; color: #64748B;">
                Already registered? <a href="<?php echo site_url('scholarshipregister/portal'); ?>" style="color: var(--pw-primary); font-weight: 700; text-decoration: underline;">Go to Candidate Portal</a>
            </div>
        </div>

        <!-- STEP 2: MOBILE ALREADY REGISTERED / MULTI-CHILD SCREEN -->
        <div id="modalStepRegistered" style="display: none;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                <h3 class="pw-modal-title" style="font-size: 20px;">Candidate Already Registered</h3>
                <span class="badge" style="background: #E8F8F2; color: #00A86B; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 20px;">
                    <i class="fa fa-check-circle"></i> +91 <span id="display_verified_mobile"></span>
                </span>
            </div>
            
            <p style="font-size: 13.5px; color: #475569; margin-bottom: 14px;">
                We found candidate(s) already registered under this mobile number:
            </p>

            <!-- Dynamic List of Registered Candidates -->
            <div id="modal_candidate_list" style="margin-bottom: 18px;"></div>

            <!-- PIN Login Box for existing candidate -->
            <div id="modal_pin_login_section" style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 18px;">
                <div style="font-weight: 700; font-size: 13.5px; color: #1E293B; margin-bottom: 8px;">
                    <i class="fa fa-lock" style="color: var(--pw-primary);"></i> Enter 4-Digit Security PIN to access Candidate Portal:
                </div>
                <input type="password" 
                       id="modal_pin_input" 
                       maxlength="4" 
                       pattern="\d{4}" 
                       placeholder="••••" 
                       class="pw-modal-pin-input">
                <div id="modal_pin_error" style="display: none; color: #EF4444; font-size: 13px; font-weight: 600; margin-top: 6px; text-align: center;"></div>
                
                <button type="button" 
                        id="btn_modal_portal_login" 
                        class="btn-submit-pw" 
                        style="width: 100%; margin-top: 10px; padding: 12px; font-size: 14.5px; border-radius: 8px;"
                        onclick="handleModalPinLogin();">
                    <i class="fa fa-sign-in"></i> Login to Candidate Portal
                </button>
            </div>

            <!-- Register Sibling / Another Child Option -->
            <div style="background: #F0EDFF; border: 1.5px dashed #A594F9; border-radius: 12px; padding: 16px; text-align: center;">
                <div style="font-weight: 800; font-size: 14.5px; color: #2B1879; margin-bottom: 4px;">
                    <i class="fa fa-users" style="color: var(--pw-primary);"></i> Have another child? Register sibling
                </div>
                <p style="font-size: 12px; color: #5B48B2; margin-bottom: 12px; line-height: 1.4;">
                    Parents can register multiple children with the same mobile number.
                </p>
                <button type="button" 
                        id="btn_modal_register_sibling" 
                        class="btn" 
                        style="width: 100%; background: #FFFFFF; color: var(--pw-primary); border: 2px solid var(--pw-primary); font-weight: 800; padding: 10px; border-radius: 8px;"
                        onclick="handleRegisterSibling();">
                    <i class="fa fa-user-plus"></i> Register Another Child
                </button>
            </div>

            <div style="text-align: center; margin-top: 16px;">
                <a href="javascript:void(0);" onclick="resetRegisterModal();" style="font-size: 13px; color: #64748B; text-decoration: underline;">
                    &larr; Use a different mobile number
                </a>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & Bootstrap Scripts -->
<script src="<?php echo base_url('backend/custom/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('backend/bootstrap/js/bootstrap.min.js'); ?>"></script>

<!-- Bilingual Translation Engine -->
<script>
var dictionary = [
    // Modal & Multi-child Translations
    ["Register for Scholarship", "छात्रवृत्ति के लिए पंजीकरण"],
    ["Mobile Number", "मोबाइल नंबर"],
    ["Verify & Proceed", "सत्यापित करें और आगे बढ़ें"],
    ["Verifying...", "सत्यापित किया जा रहा है..."],
    ["Enter your 10-digit mobile number to verify & proceed", "सत्यापित करने और आगे बढ़ने के लिए 10-अंकों का मोबाइल नंबर दर्ज करें"],
    ["Candidate Already Registered", "उम्मीदवार पहले से पंजीकृत है"],
    ["Mobile Already Registered", "मोबाइल नंबर पहले से पंजीकृत है"],
    ["We found candidate(s) already registered under this mobile number:", "इस मोबाइल नंबर पर पहले से पंजीकृत उम्मीदवार मिले:"],
    ["Enter 4-Digit Security PIN to access Candidate Portal:", "कैंडिडेट पोर्टल खोलने हेतु 4-अंकीय पासवर्ड पिन दर्ज करें:"],
    ["Login to Candidate Portal", "कैंडिडेट पोर्टल पर लॉग इन करें"],
    ["Have another child? Register sibling", "क्या दूसरा बच्चा भी है? भाई/बहन का पंजीकरण करें"],
    ["Register Another Child", "एक और बच्चे का पंजीकरण करें"],
    ["Parents can register multiple children with the same mobile number.", "अभिभावक एक ही मोबाइल नंबर से एक से अधिक बच्चों का पंजीकरण कर सकते हैं।"],
    ["Use a different mobile number", "अन्य मोबाइल नंबर का उपयोग करें"],
    ["Registering Sibling / Additional Child:", "अतिरिक्त बच्चे / भाई-बहन का पंजीकरण:"],
    ["You are registering another candidate under parent mobile", "आप अभिभावक मोबाइल के तहत एक नए छात्र का पंजीकरण कर रहे हैं"],
    ["Already registered?", "पहले से पंजीकृत हैं?"],
    ["Go to Candidate Portal", "कैंडिडेट पोर्टल पर जाएं"],

    // Top strip & Navbar
    ["NATIONAL SCHOLARSHIP", "राष्ट्रीय छात्रवृत्ति"],
    ["Sunrise Scholarship Cum Admission Test (SSAT-2026)", "सनराइज स्कॉलरशिप कम एडमिशन टेस्ट (SSAT-2026)"],
    ["Registrations Open", "पंजीकरण प्रारंभ"],
    ["100% Free Entry", "100% निःशुल्क आवेदन"],
    ["Register Online", "ऑनलाइन पंजीकरण करें"],
    ["Scholarship & Admission Wing", "छात्रवृत्ति एवं प्रवेश विंग"],
    ["About", "विवरण"],
    ["Rewards", "पुरस्कार"],
    ["How It Works", "प्रक्रिया"],
    ["Syllabus", "पाठ्यक्रम"],
    ["Dates", "तिथियां"],
    ["Candidate Portal", "कैंडिडेट पोर्टल"],
    ["Register (Free)", "पंजीकरण (निःशुल्क)"],
    ["Apply", "आवेदन"],

    // Hero Section
    ["Classes 5th to 11th • Academic Session 2026-27", "कक्षा 5वीं से 11वीं • शैक्षणिक सत्र 2026-27"],
    ["Win up to 100% Scholarship with SSAT 2026", "SSAT 2026 के साथ पाएं 100% तक छात्रवृत्ति"],
    ["Unlock premier quality school & foundation education. Benchmark yourself with thousands of students and secure grand fee waivers, cash honors and direct admission.", "शीर्ष गुणवत्तापूर्ण स्कूल व फाउंडेशन शिक्षा प्राप्त करें। हजारों विद्यार्थियों के साथ अपनी तैयारी का आकलन करें और पाएं विशेष फीस छूट, नकद पुरस्कार व सीधा प्रवेश।"],
    ["100% Fee Waiver", "100% फीस माफी"],
    ["Top rankers get complete tuition fee off", "शीर्ष रैंकर्स को मिलेगी पूर्ण ट्यूशन फीस छूट"],
    ["Merit Cash Honors", "मेधावी नकद सम्मान"],
    ["Cash prizes, mementos & medals", "नकद पुरस्कार, ट्रॉफी व मेडल"],
    ["100% NCERT Pattern", "100% NCERT पैटर्न"],
    ["Bilingual OMR MCQ question paper", "द्विभाषी OMR आधारित प्रश्न पत्र"],
    ["100% Free Registration", "100% निःशुल्क पंजीकरण"],
    ["No application or exam fee required", "कोई पंजीकरण शुल्क नहीं लगेगा"],
    ["Register Online (Free)", "ऑनलाइन पंजीकरण करें (निःशुल्क)"],
    ["Download Brochure", "विवरणिका डाउनलोड करें"],

    // Exam Details Box
    ["Exam Details", "परीक्षा विवरण"],
    ["OFFICIAL", "आधिकारिक"],
    ["Date of Examination", "परीक्षा की तिथि"],
    ["15 November 2026 (Sunday)", "15 नवंबर 2026 (रविवार)"],
    ["Format & Duration", "प्रारूप एवं समयावधि"],
    ["90 Minutes • OMR Multiple Choice", "90 मिनट • OMR बहुविकल्पीय प्रश्न (MCQs)"],
    ["Eligibility Criteria", "पात्रता मानदंड"],
    ["Classes 5th to 11th (Any recognized school)", "कक्षा 5वीं से 11वीं (किसी भी विद्यालय के विद्यार्थी)"],
    ["Scholarship Concession", "छात्रवृत्ति एवं पुरस्कार"],
    ["Up to 100% Fee Concession + Cash Honors", "100% तक फीस माफी + नकद पुरस्कार"],
    ["Registration Closing Soon", "पंजीकरण शीघ्र समाप्त हो रहा है"],
    ["Days", "दिन"],
    ["Hours", "घंटे"],
    ["Mins", "मिनट"],
    ["Secs", "सेकंड"],

    // Impact Section
    ["Tuition Fee Waiver", "ट्यूशन फीस छूट"],
    ["For top meritorious students", "शीर्ष मेधावी विद्यार्थियों के लिए"],
    ["Eligible Grades", "पात्र कक्षाएं"],
    ["From all state and national boards", "सभी राज्य एवं केंद्रीय बोर्ड्स से"],
    ["Cash Rewards & Trophies", "नकद पुरस्कार एवं ट्रॉफी"],
    ["Direct cash awards for rankers", "रैंकर्स को सीधा नकद पुरस्कार"],
    ["Registration Cost", "पंजीकरण शुल्क"],
    ["Zero exam fees for all applicants", "सभी आवेदकों के लिए पूर्णतः निःशुल्क"],

    // Steps Flow
    ["Simple Process", "सरल प्रक्रिया"],
    ["Steps To Get Scholarship", "छात्रवृत्ति प्राप्त करने के चरण"],
    ["Follow three quick steps to participate in SSAT 2026 and claim your scholarship.", "SSAT 2026 में भाग लेने और छात्रवृत्ति प्राप्त करने हेतु इन 3 आसान चरणों का पालन करें।"],
    ["Register Online for Free", "1. निःशुल्क ऑनलाइन पंजीकरण करें"],
    ["Fill out the simple application form below with your class & mobile number. Set your 4-digit security PIN.", "नीचे दिए गए आसान फॉर्म में अपनी कक्षा, मोबाइल नंबर भरें और 4-अंकों का पासवर्ड पिन बनाएं।"],
    ["Download Model Paper & Prepare", "2. मॉडल पेपर डाउनलोड कर तैयारी करें"],
    ["Log in to your Candidate Portal using your Roll No and PIN to practice with authentic class-wise bilingual sample papers.", "कैंडिडेट पोर्टल में अपने रोल नंबर और पिन से लॉगिन करके कक्षा-अनुसार मॉडल पेपर डाउनलोड करें।"],
    ["Appear for Exam & Win", "3. परीक्षा में शामिल हों और जीतें"],
    ["Attend the OMR test on 15 Nov 2026. Secure your All India Rank, cash reward, and up to 100% school fee concession.", "15 नवंबर 2026 को परीक्षा दें। अपनी रैंक, नकद पुरस्कार और 100% तक फीस रियायत प्राप्त करें।"],

    // Rewards Section
    ["Rewards & Honors", "पुरस्कार एवं सम्मान"],
    ["What You Win with SSAT 2026", "SSAT 2026 से आपको क्या मिलेगा"],
    ["Empowering talented students with financial assistance and prestigious accolades.", "प्रतिभाशाली विद्यार्थियों को आर्थिक सहायता और प्रतिष्ठित सम्मान देकर सशक्त बनाना।"],
    ["Top Privilege", "विशेष सुविधा"],
    ["Up to 100% Fee Waiver", "100% तक स्कूल फीस छूट"],
    ["Ranks 1 to 10 in each class get up to 100% complete waiver on tuition fees for the entire academic session 2026-27.", "प्रत्येक कक्षा के रैंक 1 से 10 तक के मेधावी विद्यार्थियों को सत्र 2026-27 के लिए ट्यूशन फीस में 100% तक छूट।"],
    ["Cash Honors", "नकद पुरस्कार"],
    ["Cash Awards & Mementos", "नकद पुरस्कार एवं स्मृति चिन्ह"],
    ["Substantial cash rewards, personalized trophies, certificates of excellence, and medals awarded in a grand ceremony.", "भव्य सम्मान समारोह में आकर्षक नकद पुरस्कार, ट्रॉफी, मेडल और मेरिट प्रमाण पत्र।"],
    ["Guaranteed", "निश्चित लाभ"],
    ["Priority Direct Admission", "प्राथमिकता सीधा प्रवेश"],
    ["Merit holders receive assured direct admission into Foundation, Sainik/Military, and Pre-Medical/Pre-Engineering wings.", "सफल छात्रों को फाउंडेशन, सैनिक/मिलिट्री स्कूल और प्री-मेडिकल/इंजीनियरिंग विंग में सीधा प्रवेश।"],

    // Syllabus
    ["Examination Pattern", "परीक्षा प्रारूप"],
    ["Syllabus & Question Blueprint", "पाठ्यक्रम एवं प्रश्न पत्र प्रारूप"],
    ["Prepared strictly according to 100% NCERT textbook curriculum. All questions are bilingual (Hindi & English).", "100% NCERT पाठ्यपुस्तक पाठ्यक्रम पर आधारित। सभी प्रश्न हिंदी और अंग्रेजी दोनों भाषाओं में होंगे।"],
    ["Class Group", "कक्षा समूह"],
    ["Subjects Covered", "सम्मिलित विषय"],
    ["Total Questions", "कुल प्रश्न"],
    ["Maximum Marks", "पूर्णांक"],
    ["Exam Mode", "परीक्षा माध्यम"],
    ["Class 5th", "कक्षा 5वीं"],
    ["Class 6th to 10th", "कक्षा 6वीं से 10वीं"],
    ["Class 11th Science", "कक्षा 11वीं विज्ञान"],
    ["Class 11th Arts", "कक्षा 11वीं कला"],
    ["Hindi, English, Mathematics, EVS", "हिंदी, अंग्रेजी, गणित, पर्यावरण (EVS)"],
    ["Hindi, English, Science, Mathematics, Social Studies, Logical Reasoning", "हिंदी, अंग्रेजी, विज्ञान, गणित, सामाजिक विज्ञान, रीजनिंग"],
    ["English, Mathematics / Biology, Physics, Chemistry", "अंग्रेजी, गणित / जीव विज्ञान, भौतिकी, रसायन विज्ञान"],
    ["English, Political Science, History, Geography", "अंग्रेजी, राजनीति विज्ञान, इतिहास, भूगोल"],

    // Dates
    ["Key Milestones", "मुख्य कार्यक्रम"],
    ["Important Examination Dates", "परीक्षा की महत्वपूर्ण तिथियां"],
    ["Mark these essential schedule dates on your calendar.", "पंजीकरण, प्रवेश पत्र और परीक्षा की तिथियां अवश्य नोट कर लें।"],
    ["Registration Commencement", "पंजीकरण प्रारंभ तिथि"],
    ["Online & offline application window opens for all students", "सभी छात्रों के लिए ऑनलाइन एवं ऑफलाइन आवेदन शुरू"],
    ["Registration Closing Date", "पंजीकरण की अंतिम तिथि"],
    ["Last date to submit application forms without late penalty", "बिना विलंब शुल्क आवेदन फॉर्म सबमिट करने की अंतिम तिथि"],
    ["Admit Card Release Date", "प्रवेश पत्र जारी होने की तिथि"],
    ["Admit card available for download via Candidate Portal & SMS", "कैंडिडेट पोर्टल और एसएमएस द्वारा प्रवेश पत्र डाउनलोड शुरू"],
    ["Examination Date & Reporting", "परीक्षा तिथि एवं रिपोर्टिंग समय"],
    ["15 November 2026, 10:00 AM (Reporting 9:30 AM)", "15 नवंबर 2026, प्रातः 10:00 बजे (रिपोर्टिंग 9:30 AM)"],
    ["Result Announcement", "परीक्षा परिणाम की घोषणा"],
    ["Merit rank list published on portal & school notice boards", "पोर्टल एवं विद्यालय सूचना पट्ट पर मेरिट सूची का प्रकाशन"],
    ["Award & Felicitation Ceremony", "पुरस्कार एवं सम्मान समारोह"],
    ["Grand ceremony for cash prizes, mementos & certificates", "नकद पुरस्कार, ट्रॉफी व प्रमाण पत्र वितरण हेतु भव्य समारोह"],

    // Form
    ["Candidate Registration Form", "विद्यार्थी ऑनलाइन पंजीकरण फॉर्म"],
    ["Fill in your details below to register. Roll Number & Admit Card will be generated instantaneously.", "पंजीकरण के लिए नीचे विवरण भरें। रोल नंबर एवं प्रवेश पत्र तुरंत जनरेट होगा।"],
    ["Candidate Full Name *", "विद्यार्थी का पूरा नाम *"],
    ["Present School Type *", "वर्तमान स्कूल का प्रकार *"],
    ["Sunrise Student", "सनराइज का विद्यार्थी"],
    ["Other School", "अन्य स्कूल का विद्यार्थी"],
    ["Primary Mobile Number (WhatsApp) *", "प्राथमिक मोबाइल नंबर (व्हाट्सएप) *"],
    ["Present Class / Grade *", "वर्तमान कक्षा / क्लास *"],
    ["Father's Name", "पिता का नाम"],
    ["Email Address", "ईमेल पता"],
    ["Gender", "लिंग"],
    ["City / District", "शहर / जिला"],
    ["Set 4-Digit Password PIN *", "4-अंकों का पासवर्ड पिन बनाएं *"],
    ["Submit Application & Generate Roll Slip", "आवेदन सबमिट करें एवं रोल नंबर पर्ची प्राप्त करें"],
    ["Candidate Portal Access:", "कैंडिडेट पोर्टल सुविधा:"],
    ["Use your Roll Number and this 4-digit PIN to login to your dashboard to download class-wise sample papers and admit card.", "मॉडल पेपर और प्रवेश पत्र डाउनलोड करने के लिए अपने रोल नंबर व इस 4-अंकों के पिन से लॉगिन करें।"],

    // FAQs
    ["Help & Support", "सहायता एवं समर्थन"],
    ["Frequently Asked Questions", "अक्सर पूछे जाने वाले प्रश्न (FAQs)"],
    ["Have questions about the scholarship test? Find your answers here.", "छात्रवृत्ति परीक्षा से जुड़े सामान्य प्रश्नों के उत्तर यहां देखें।"],
    ["Who is eligible for the Sunrise Scholarship Test?", "सनराइज छात्रवृत्ति परीक्षा के लिए कौन पात्र है?"],
    ["Students presently studying in Classes 5th to 11th in any recognized school (CBSE, RBSE, ICSE or State Boards) are eligible to participate. Both English and Hindi medium students are welcomed.", "किसी भी मान्यता प्राप्त विद्यालय (CBSE, RBSE, ICSE या राज्य बोर्ड) में कक्षा 5वीं से 11वीं में अध्ययनरत सभी विद्यार्थी पात्र हैं। हिंदी एवं अंग्रेजी दोनों माध्यमों के विद्यार्थी भाग ले सकते हैं।"],
    ["What is the syllabus and medium of examination?", "परीक्षा का पाठ्यक्रम एवं माध्यम क्या है?"],
    ["The examination is conducted strictly as per NCERT textbook syllabus of the candidate's current class. The test paper is bilingual containing both Hindi and English for all questions.", "परीक्षा विद्यार्थी की वर्तमान कक्षा के NCERT पाठ्यक्रम पर आधारित है। सभी प्रश्न हिंदी और अंग्रेजी दोनों भाषाओं में द्विभाषी होंगे।"],
    ["Is there any registration fee?", "क्या कोई पंजीकरण शुल्क है?"],
    ["Online registration for SSAT-2026 is completely FREE of cost! You can register online, via WhatsApp helpline (+91 9783200821), or in-person at the school reception.", "SSAT-2026 के लिए ऑनलाइन पंजीकरण पूरी तरह से निःशुल्क (FREE) है! आप ऑनलाइन, व्हाट्सएप हेल्पलाइन (+91 9783200821) या स्कूल रिसेप्शन पर पंजीकरण करा सकते हैं।"],
    ["How will the scholarship awards be distributed?", "छात्रवृत्ति एवं पुरस्कार कैसे दिए जाएंगे?"],
    ["Top meritorious rankers will receive up to 100% concession on tuition fees for the 2026-27 session, along with cash awards, mementos, medals, and official certificates of excellence.", "शीर्ष मेधावी विद्यार्थियों को सत्र 2026-27 हेतु 100% तक ट्यूशन फीस में छूट, साथ ही नकद पुरस्कार, स्मृति चिन्ह, मेडल व मेरिट प्रमाण पत्र प्रदान किए जाएंगे।"],

    // Mobile CTA
    ["Apply Now (Free)", "अभी आवेदन करें (निःशुल्क)"],
    ["Chat", "व्हाट्सएप"]
];

function translatePageTo(lang) {
    var walker = document.createTreeWalker(
        document.body,
        NodeFilter.SHOW_TEXT,
        {
            acceptNode: function(node) {
                var parent = node.parentElement;
                if (!parent) return NodeFilter.FILTER_REJECT;
                var tag = parent.tagName.toLowerCase();
                if (tag === 'script' || tag === 'style' || tag === 'noscript' || tag === 'textarea' || tag === 'code' || parent.classList.contains('lang-switch-btn')) {
                    return NodeFilter.FILTER_REJECT;
                }
                return NodeFilter.FILTER_ACCEPT;
            }
        },
        false
    );

    var textNodes = [];
    while (walker.nextNode()) {
        textNodes.push(walker.currentNode);
    }

    textNodes.forEach(function(node) {
        if (!node.originalEnglish) {
            node.originalEnglish = node.nodeValue;
        }

        if (lang === 'hi') {
            var currentVal = node.nodeValue;
            for (var i = 0; i < dictionary.length; i++) {
                var en = dictionary[i][0];
                var hi = dictionary[i][1];
                if (currentVal.indexOf(en) !== -1) {
                    currentVal = currentVal.split(en).join(hi);
                }
            }
            node.nodeValue = currentVal;
        } else {
            node.nodeValue = node.originalEnglish;
        }
    });

    // Placeholders
    document.querySelectorAll('input[placeholder]').forEach(function(inp) {
        if (!inp.getAttribute('data-orig-ph')) {
            inp.setAttribute('data-orig-ph', inp.getAttribute('placeholder'));
        }
        var origPh = inp.getAttribute('data-orig-ph');
        if (lang === 'hi') {
            if (origPh.indexOf('full name') !== -1) inp.setAttribute('placeholder', 'विद्यार्थी का पूरा नाम लिखें');
            else if (origPh.indexOf('mobile') !== -1) inp.setAttribute('placeholder', '10-अंकों का मोबाइल नंबर');
            else if (origPh.indexOf('Father') !== -1) inp.setAttribute('placeholder', 'पिता का पूरा नाम');
            else if (origPh.indexOf('school name') !== -1) inp.setAttribute('placeholder', 'स्कूल का नाम लिखें');
            else if (origPh.indexOf('City') !== -1) inp.setAttribute('placeholder', 'शहर या जिला');
        } else {
            inp.setAttribute('placeholder', origPh);
        }
    });
}

function setLanguage(lang) {
    localStorage.setItem('ssat_lang', lang);
    document.querySelectorAll('.lang-switch-btn').forEach(function(b) {
        b.classList.remove('active');
    });

    if (lang === 'hi') {
        var btnHi = document.getElementById('btn_lang_hi');
        var mBtnHi = document.getElementById('m_btn_lang_hi');
        if (btnHi) btnHi.classList.add('active');
        if (mBtnHi) mBtnHi.classList.add('active');
    } else {
        var btnEn = document.getElementById('btn_lang_en');
        var mBtnEn = document.getElementById('m_btn_lang_en');
        if (btnEn) btnEn.classList.add('active');
        if (mBtnEn) mBtnEn.classList.add('active');
    }

    translatePageTo(lang);
}

document.addEventListener('DOMContentLoaded', function() {
    var savedLang = localStorage.getItem('ssat_lang') || 'en';
    if (savedLang === 'hi') {
        setLanguage('hi');
    }
});
</script>

<!-- Live Countdown Logic -->
<script>
(function() {
    var examDate = new Date("2026-11-15T10:00:00").getTime();
    function updateCountdown() {
        var now = new Date().getTime();
        var distance = examDate - now;
        if (distance < 0) distance = 0;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        var dEl = document.getElementById("cd_days");
        var hEl = document.getElementById("cd_hours");
        var mEl = document.getElementById("cd_mins");
        var sEl = document.getElementById("cd_secs");

        if (dEl) dEl.innerText = (days < 10 ? "0" : "") + days;
        if (hEl) hEl.innerText = (hours < 10 ? "0" : "") + hours;
        if (mEl) mEl.innerText = (minutes < 10 ? "0" : "") + minutes;
        if (sEl) sEl.innerText = (seconds < 10 ? "0" : "") + seconds;
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);
})();
</script>

<!-- Registration Modal Logic -->
<script>
var currentVerifiedMobile = '';
var registeredCandidates = [];

function openRegisterModal(e) {
    if (e && e.preventDefault) e.preventDefault();
    $('#pwRegisterModal').css('display', 'flex').hide().fadeIn(150).addClass('active');
    $('body').css('overflow', 'hidden');
    resetRegisterModal();
    setTimeout(function() {
        $('#modal_mobile_input').focus();
    }, 200);
}

function closeRegisterModal() {
    $('#pwRegisterModal').removeClass('active').fadeOut(150);
    $('body').css('overflow', '');
}

function resetRegisterModal() {
    $('#modalStepRegistered').hide();
    $('#modalStepMobile').show();
    $('#modal_mobile_input').val('').trigger('input');
    $('#modal_mobile_error').hide().text('');
    $('#modal_pin_input').val('');
    $('#modal_pin_error').hide().text('');
}

// Close on backdrop click (outside card)
$(document).on('click', '#pwRegisterModal', function(e) {
    if ($(e.target).is('#pwRegisterModal')) {
        closeRegisterModal();
    }
});

// Intercept all register & apply triggers
$(document).on('click', 'a[href="#register"], .btn-register-hero, .btn-pw-nav', function(e) {
    e.preventDefault();
    openRegisterModal();
});

// Mobile Input formatting and button state
$(document).on('input', '#modal_mobile_input', function() {
    var raw = $(this).val().replace(/\D/g, '');
    $(this).val(raw);
    var btn = $('#modal_verify_btn');
    if (raw.length === 10) {
        btn.removeClass('pw-modal-btn-disabled').addClass('pw-modal-btn-active').prop('disabled', false);
        $('#modal_mobile_error').hide();
    } else {
        btn.removeClass('pw-modal-btn-active').addClass('pw-modal-btn-disabled').prop('disabled', true);
    }
});

$(document).on('keypress', '#modal_mobile_input', function(e) {
    if (e.which === 13 && $(this).val().length === 10) {
        handleVerifyMobile();
    }
});

// Handle Verify & Proceed
function handleVerifyMobile() {
    var mobile = $('#modal_mobile_input').val().trim();
    if (mobile.length !== 10) {
        $('#modal_mobile_error').text('Please enter a valid 10-digit mobile number.').show();
        return;
    }

    var btn = $('#modal_verify_btn');
    var origHtml = btn.html();
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Verifying...');
    $('#modal_mobile_error').hide();

    $.ajax({
        url: '<?php echo site_url("scholarshipregister/check_mobile"); ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            mobile: mobile,
            exam_id: '<?php echo isset($selected_exam["id"]) ? $selected_exam["id"] : 1; ?>'
        },
        success: function(res) {
            btn.prop('disabled', false).html(origHtml);
            currentVerifiedMobile = mobile;

            if (res.status === 'registered') {
                registeredCandidates = res.candidates || [];
                renderRegisteredCandidates(registeredCandidates);
                $('#display_verified_mobile').text(mobile);
                $('#modalStepMobile').hide();
                $('#modalStepRegistered').fadeIn(200);
                if (localStorage.getItem('ssat_lang') === 'hi') {
                    translatePageTo('hi');
                }
                setTimeout(function() {
                    $('#modal_pin_input').focus();
                }, 250);
            } else if (res.status === 'new') {
                btn.html('<i class="fa fa-check"></i> Verified!').css('background', 'var(--pw-green)');
                setTimeout(function() {
                    closeRegisterModal();
                    scrollToRegisterForm(mobile, false);
                }, 350);
            } else {
                $('#modal_mobile_error').text(res.message || 'Validation error. Please check number.').show();
            }
        },
        error: function() {
            btn.prop('disabled', false).html(origHtml);
            closeRegisterModal();
            scrollToRegisterForm(mobile, false);
        }
    });
}

function renderRegisteredCandidates(candidates) {
    var container = $('#modal_candidate_list');
    container.empty();

    if (!candidates || candidates.length === 0) {
        container.html('<div class="alert alert-warning" style="font-size: 13px;">No candidate details found.</div>');
        return;
    }

    var html = '';
    for (var i = 0; i < candidates.length; i++) {
        var c = candidates[i];
        var isChecked = (i === 0) ? 'checked' : '';
        var activeClass = (i === 0) ? 'active' : '';
        html += '<label class="candidate-select-card ' + activeClass + '">' +
                '  <div style="display: flex; align-items: center; gap: 12px;">' +
                (candidates.length > 1 ? '    <input type="radio" name="selected_candidate_id" value="' + c.id + '" ' + isChecked + ' onchange="updateSelectedCandidateCard(this);" style="accent-color: var(--pw-primary); width: 18px; height: 18px;">' : '<input type="hidden" name="selected_candidate_id" value="' + c.id + '">') +
                '    <div>' +
                '      <div style="font-weight: 800; font-size: 15px; color: #0F172A;">' + $('<div>').text(c.name).html() + '</div>' +
                '      <div style="font-size: 12.5px; color: #64748B;">' + $('<div>').text(c.class_name).html() + ' &bull; Roll: <strong style="color: var(--pw-primary);">' + $('<div>').text(c.roll_no).html() + '</strong></div>' +
                '    </div>' +
                '  </div>' +
                '  <span class="badge" style="background: #E8F8F2; color: #00A86B; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 6px;">Registered</span>' +
                '</label>';
    }
    container.html(html);
}

function updateSelectedCandidateCard(radio) {
    $('.candidate-select-card').removeClass('active');
    $(radio).closest('.candidate-select-card').addClass('active');
}

// PIN Login from Modal
function handleModalPinLogin() {
    var pin = $('#modal_pin_input').val().trim();
    if (pin.length !== 4) {
        $('#modal_pin_error').text('Please enter your 4-digit security PIN.').show();
        return;
    }

    var candidateId = $('input[name="selected_candidate_id"]:checked').val() || $('input[name="selected_candidate_id"]').val();
    var btn = $('#btn_modal_portal_login');
    var origHtml = btn.html();
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Logging in...');
    $('#modal_pin_error').hide();

    $.ajax({
        url: '<?php echo site_url("scholarshipregister/portal_login_ajax"); ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            candidate_id: candidateId,
            login_identifier: currentVerifiedMobile,
            password_pin: pin
        },
        success: function(res) {
            if (res.status === 'success') {
                btn.html('<i class="fa fa-check"></i> ' + res.message).css('background', 'var(--pw-green)');
                window.location.href = res.redirect_url;
            } else {
                btn.prop('disabled', false).html(origHtml);
                $('#modal_pin_error').text(res.message || 'Incorrect PIN. Please try again.').show();
            }
        },
        error: function() {
            btn.prop('disabled', false).html(origHtml);
            $('#modal_pin_error').text('Server error. Please log in directly via the Candidate Portal.').show();
        }
    });
}

$(document).on('keypress', '#modal_pin_input', function(e) {
    if (e.which === 13 && $(this).val().length === 4) {
        handleModalPinLogin();
    }
});

// Sibling / Another child registration
function handleRegisterSibling() {
    closeRegisterModal();
    scrollToRegisterForm(currentVerifiedMobile, true);
}

function scrollToRegisterForm(mobile, isSibling) {
    var target = $('#register');
    if (target.length) {
        $('html, body').animate({
            scrollTop: target.offset().top - 80
        }, 500);
    }

    $('#form_candidate_mobile').val(mobile);

    if (isSibling) {
        $('#sibling_notice_box').html(
            '<div class="alert alert-info" style="border-radius: 12px; background: #EFF6FF; border: 1.5px solid #93C5FD; color: #1E40AF; padding: 14px 18px; margin-bottom: 25px;">' +
            '  <i class="fa fa-users" style="font-size: 18px; margin-right: 8px; vertical-align: middle;"></i> ' +
            '  <strong>Registering Sibling / Additional Child:</strong> You are registering another candidate under parent mobile <strong>+91 ' + $('<div>').text(mobile).html() + '</strong>.' +
            '</div>'
        ).show();
        $('#form_candidate_name').val('').focus();
        $('#form_password_pin').val('');
        $('#form_class_id').val('');
    } else {
        $('#sibling_notice_box').html(
            '<div class="alert alert-success" style="border-radius: 12px; background: #E8F8F2; border: 1.5px solid #A7F3D0; color: #065F46; padding: 12px 18px; margin-bottom: 25px;">' +
            '  <i class="fa fa-check-circle" style="font-size: 16px; margin-right: 6px; vertical-align: middle;"></i> ' +
            '  Mobile <strong>+91 ' + $('<div>').text(mobile).html() + '</strong> verified! Please complete candidate application details below.' +
            '</div>'
        ).show();
        $('#form_candidate_name').focus();
    }

    if (localStorage.getItem('ssat_lang') === 'hi') {
        translatePageTo('hi');
    }
}
</script>

</body>
</html>