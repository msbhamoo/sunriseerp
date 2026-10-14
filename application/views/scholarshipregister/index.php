<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($selected_exam['title']) ? htmlspecialchars($selected_exam['title']) . ' - Admission & Scholarship Test 2026' : 'Sunrise Scholarship Cum Admission Test 2026'; ?></title>
    <meta name="description" content="Apply for Sunrise Scholarship Cum Admission Test (SSAT-2026). Up to 100% Scholarship in School Fees, Cash Awards, Mementos, and Merit Certificates. Open for Classes 5th to 11th.">
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('backend/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,500;0,600;0,700;0,800;1,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-950: #0a1426;
            --navy-900: #0f1d38;
            --navy-800: #192a4e;
            --navy-700: #223a6b;
            --oxford-blue: #14213d;
            --gold-600: #996515;
            --gold-500: #b8860b;
            --gold-400: #c59b27;
            --gold-100: #fbf5e6;
            --gold-50: #fdfaf2;
            --cream-50: #fcfbf7;
            --cream-100: #f6f3ea;
            --neutral-900: #1c1917;
            --neutral-800: #292524;
            --neutral-700: #44403c;
            --neutral-600: #57534e;
            --neutral-500: #78716c;
            --neutral-300: #d6d3d1;
            --neutral-200: #e7e5e4;
            --neutral-100: #f5f5f4;
            --emerald-800: #065f46;
            --emerald-700: #047857;
            --emerald-50: #ecfdf5;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--cream-50);
            color: var(--neutral-800);
            line-height: 1.65;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            padding-bottom: 70px;
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
        }

        @media (min-width: 769px) {
            body { padding-bottom: 0; }
        }

        h1, h2, h3, h4, .font-serif, .hero-title, .section-title, .award-card h4, .hero-glance-heading {
            font-family: 'Crimson Pro', Georgia, serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Top Institutional Notification Ribbon */
        .top-notification-strip {
            background: var(--navy-950);
            color: #e2e8f0;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 16px;
            text-align: center;
            border-bottom: 1px solid rgba(184, 134, 11, 0.35);
        }
        .top-notification-strip .notice-badge {
            background: var(--gold-500);
            color: #0f172a;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 8px;
            display: inline-block;
        }
        .top-notification-strip a {
            color: var(--gold-400);
            text-decoration: none;
            margin-left: 10px;
            font-weight: 600;
            border-bottom: 1px dotted var(--gold-400);
            transition: color 0.2s;
        }
        .top-notification-strip a:hover {
            color: #ffffff;
            border-bottom-style: solid;
        }

        /* Top Editorial Navigation Header */
        .landing-nav {
            background: #ffffff;
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 14px 0;
            border-bottom: 1px solid var(--neutral-200);
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .school-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }
        .school-brand:hover, .school-brand:focus {
            text-decoration: none;
        }
        .school-brand-icon {
            width: 44px;
            height: 44px;
            background: var(--navy-900);
            border: 1px solid var(--gold-500);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-400);
            font-size: 20px;
            flex-shrink: 0;
        }
        .school-brand-text h4 {
            margin: 0;
            font-size: 19px;
            font-weight: 700;
            color: var(--navy-950);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }
        .school-brand-text span {
            font-size: 11px;
            color: var(--neutral-500);
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 22px;
            justify-content: flex-end;
        }
        .nav-links a {
            color: var(--neutral-700);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .nav-links a:hover {
            color: var(--navy-900);
            text-decoration: none;
        }
        .nav-btn-apply {
            background: var(--navy-900);
            color: #ffffff !important;
            padding: 8px 20px;
            border-radius: 6px;
            border: 1px solid var(--navy-950);
            font-weight: 600 !important;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }
        .nav-btn-apply:hover {
            background: var(--navy-800);
            transform: translateY(-1px);
        }

        /* Hero Section — Rich Institutional Banner */
        .hero-section {
            background: var(--navy-950);
            color: #ffffff;
            padding: 60px 0 75px 0;
            position: relative;
            border-bottom: 3px solid var(--gold-500);
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(184, 134, 11, 0.12);
            border: 1px solid var(--gold-500);
            color: var(--gold-400);
            padding: 5px 14px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .hero-title {
            font-size: 44px;
            line-height: 1.15;
            margin-bottom: 18px;
            color: #ffffff;
            font-weight: 700;
        }
        .hero-title .title-accent {
            color: var(--gold-400);
            font-style: italic;
            font-weight: 600;
        }
        .hero-subtitle {
            font-size: 17px;
            color: #cbd5e1;
            margin-bottom: 28px;
            max-width: 600px;
            line-height: 1.65;
            font-weight: 400;
        }
        .hero-highlights-list {
            list-style: none;
            padding: 0;
            margin: 0 0 32px 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .hero-highlights-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14.5px;
            color: #e2e8f0;
            font-weight: 500;
        }
        .hero-highlights-list i {
            color: var(--gold-400);
            font-size: 15px;
            flex-shrink: 0;
        }
        .hero-cta-group {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: center;
        }
        .btn-hero-primary {
            background: var(--gold-500);
            color: #0f172a !important;
            font-size: 15px;
            font-weight: 700;
            padding: 13px 28px;
            border-radius: 6px;
            text-decoration: none;
            border: 1px solid var(--gold-400);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            letter-spacing: 0.2px;
        }
        .btn-hero-primary:hover {
            background: #d49c12;
            color: #000000 !important;
            transform: translateY(-2px);
            text-decoration: none;
            box-shadow: 0 6px 18px rgba(184, 134, 11, 0.3);
        }
        .btn-hero-outline {
            background: transparent;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 14.5px;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: #ffffff;
            text-decoration: none;
        }

        /* Countdown Strip in Hero */
        .countdown-container {
            background: rgba(10, 20, 38, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            padding: 14px 18px;
            margin-top: 25px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .countdown-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--gold-400);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .countdown-boxes {
            display: flex;
            gap: 8px;
        }
        .count-box {
            background: var(--navy-900);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            min-width: 50px;
            padding: 6px 4px;
            text-align: center;
        }
        .count-num {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.1;
            font-family: 'Inter', sans-serif;
        }
        .count-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Hero Right Card (Institutional Registry Card) */
        .hero-glance-card {
            background: #ffffff;
            border: 1px solid var(--neutral-200);
            border-top: 4px solid var(--navy-900);
            border-radius: 8px;
            padding: 28px 26px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
            color: var(--neutral-800);
        }
        .hero-glance-heading {
            font-size: 22px;
            font-weight: 700;
            color: var(--navy-950);
            margin: 0 0 16px 0;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--neutral-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .hero-glance-heading .official-seal-tag {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--gold-600);
            background: var(--gold-100);
            padding: 3px 8px;
            border-radius: 4px;
        }
        .glance-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px solid var(--neutral-100);
        }
        .glance-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .glance-icon {
            width: 38px;
            height: 38px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .glance-icon.blue { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .glance-icon.amber { background: var(--gold-100); color: var(--gold-600); border: 1px solid #fed7aa; }
        .glance-icon.green { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .glance-icon.purple { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
        .glance-label {
            font-size: 11.5px;
            color: var(--neutral-500);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 600;
        }
        .glance-val {
            font-size: 15px;
            font-weight: 600;
            color: var(--neutral-900);
            margin-top: 1px;
        }

        /* Stats Bar — Clean Institutional Ledger */
        .stats-bar-section {
            background: #ffffff;
            border-bottom: 1px solid var(--neutral-200);
            padding: 24px 0;
        }
        .stat-item {
            text-align: center;
            padding: 8px 15px;
            border-right: 1px solid var(--neutral-200);
        }
        .stat-item:last-child {
            border-right: none;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--navy-950);
            font-family: 'Crimson Pro', Georgia, serif;
            line-height: 1;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: var(--neutral-600);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        /* Section Layout & Headers */
        .section-padding {
            padding: 65px 0;
        }
        .section-header {
            text-align: center;
            max-width: 660px;
            margin: 0 auto 45px auto;
        }
        .section-badge {
            display: inline-block;
            color: var(--gold-600);
            background: var(--gold-100);
            border: 1px solid #fde68a;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 34px;
            color: var(--navy-950);
            margin: 0 0 10px 0;
            font-weight: 700;
        }
        .section-subtitle {
            font-size: 15.5px;
            color: var(--neutral-600);
            margin: 0;
            line-height: 1.6;
        }

        /* Award Cards — Prestigious Academic Honor Boxes */
        .award-card {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid var(--neutral-200);
            border-top: 3px solid var(--neutral-300);
            padding: 32px 24px;
            text-align: center;
            height: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .award-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            border-color: var(--neutral-300);
        }
        .award-card.card-gold { border-top-color: var(--gold-500); }
        .award-card.card-cash { border-top-color: var(--emerald-700); }
        .award-card.card-cert { border-top-color: var(--navy-700); }

        .award-icon-box {
            width: 64px;
            height: 64px;
            margin: 0 auto 18px auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }
        .award-icon-box.gold { background: var(--gold-100); color: var(--gold-600); border: 1px solid #fde68a; }
        .award-icon-box.cash { background: var(--emerald-50); color: var(--emerald-700); border: 1px solid #a7f3d0; }
        .award-icon-box.cert { background: #eff6ff; color: var(--navy-700); border: 1px solid #bfdbfe; }
        .award-card h4 {
            font-size: 23px;
            font-weight: 700;
            color: var(--navy-950);
            margin-bottom: 10px;
        }
        .award-card p {
            color: var(--neutral-600);
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        /* 4-Step Process Guide */
        .step-card {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid var(--neutral-200);
            padding: 26px 20px;
            text-align: center;
            height: 100%;
            transition: all 0.2s ease;
        }
        .step-card:hover {
            border-color: var(--navy-700);
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }
        .step-number {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--navy-900);
            color: #ffffff;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px auto;
            border: 2px solid var(--gold-500);
        }
        .step-card h5 {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy-950);
            margin-bottom: 6px;
        }
        .step-card p {
            font-size: 13px;
            color: var(--neutral-600);
            margin: 0;
            line-height: 1.5;
        }

        /* Syllabus Table & Tab Navigation */
        .syllabus-container {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid var(--neutral-200);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            overflow: hidden;
        }
        .syllabus-tabs {
            display: flex;
            background: var(--navy-950);
            padding: 6px 8px;
            overflow-x: auto;
            gap: 4px;
            border-bottom: 1px solid var(--navy-800);
        }
        .syllabus-tab-btn {
            background: transparent;
            color: #94a3b8;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }
        .syllabus-tab-btn:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }
        .syllabus-tab-btn.active {
            background: var(--gold-500);
            color: #0f172a;
            font-weight: 700;
        }
        .syllabus-table-wrapper {
            overflow-x: auto;
        }
        .custom-syllabus-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }
        .custom-syllabus-table thead th {
            background: var(--navy-900);
            color: #ffffff;
            font-weight: 600;
            font-size: 13px;
            padding: 14px 16px;
            text-align: center;
            border: none;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .custom-syllabus-table thead th:first-child {
            text-align: left;
        }
        .custom-syllabus-table tbody td {
            padding: 16px;
            font-size: 13.5px;
            vertical-align: middle;
            border-bottom: 1px solid var(--neutral-100);
        }
        .custom-syllabus-table tbody tr:hover {
            background-color: var(--cream-50);
        }
        .class-pill {
            display: inline-block;
            background: var(--cream-100);
            color: var(--navy-950);
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid var(--neutral-300);
            font-size: 13px;
        }

        /* Timeline Roadmap Grid */
        .dates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 14px;
        }
        .date-box {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid var(--neutral-200);
            padding: 20px 16px;
            text-align: center;
            border-top: 3px solid var(--navy-700);
            transition: all 0.2s ease;
        }
        .date-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }
        .date-box.highlight {
            border-top-color: #dc2626;
            background: #fef2f2;
        }
        .date-box.exam-day {
            border-top-color: var(--gold-500);
            background: var(--gold-50);
        }
        .date-box-label {
            font-size: 11px;
            color: var(--neutral-500);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
        .date-box-val {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy-950);
            margin-top: 6px;
            font-family: 'Crimson Pro', Georgia, serif;
        }

        /* WhatsApp & Helpdesk Alternate Banner */
        .alt-methods-banner {
            background: #ffffff;
            border: 1px solid var(--neutral-200);
            border-left: 5px solid #16a34a;
            border-radius: 8px;
            padding: 22px 26px;
            margin-bottom: 35px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .whatsapp-btn {
            background: #16a34a;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .whatsapp-btn:hover {
            background: #15803d;
            color: #ffffff !important;
            text-decoration: none;
        }

        /* FAQ Accordion */
        .faq-card {
            background: #ffffff;
            border: 1px solid var(--neutral-200);
            border-radius: 6px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .faq-header {
            padding: 16px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            font-size: 15px;
            color: var(--neutral-900);
            background: #ffffff;
            user-select: none;
        }
        .faq-header i {
            transition: transform 0.25s;
            color: var(--navy-700);
            font-size: 13px;
        }
        .faq-card.open .faq-header i {
            transform: rotate(180deg);
        }
        .faq-body {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            font-size: 14px;
            color: var(--neutral-600);
            line-height: 1.6;
        }
        .faq-card.open .faq-body {
            padding: 0 20px 18px 20px;
            max-height: 300px;
        }

        /* Official Candidate Registration Form Card */
        .reg-section {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid var(--neutral-200);
            border-top: 4px solid var(--navy-900);
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            padding: 40px;
            margin-top: -25px;
            position: relative;
            z-index: 10;
        }
        .reg-section-header {
            text-align: center;
            border-bottom: 1px solid var(--neutral-200);
            padding-bottom: 22px;
            margin-bottom: 30px;
        }
        .reg-section-header h2 {
            font-size: 30px;
            color: var(--navy-950);
            margin: 0 0 6px 0;
            font-weight: 700;
        }
        .school-toggle-group {
            display: flex;
            gap: 8px;
            background: var(--neutral-100);
            padding: 4px;
            border-radius: 6px;
            margin-top: 6px;
            border: 1px solid var(--neutral-200);
        }
        .school-toggle-label {
            flex: 1;
            text-align: center;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            margin: 0;
            transition: all 0.2s;
            color: var(--neutral-700);
        }
        .school-toggle-label input {
            display: none;
        }
        .school-toggle-label.active {
            background: #ffffff;
            color: var(--navy-950);
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            border: 1px solid var(--neutral-300);
        }
        .form-control {
            border-radius: 6px;
            border: 1px solid var(--neutral-300);
            padding: 10px 14px;
            height: auto;
            font-size: 14px;
            box-shadow: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: #ffffff;
            color: var(--neutral-900);
        }
        .form-control:focus {
            border-color: var(--navy-700);
            box-shadow: 0 0 0 3px rgba(20, 33, 61, 0.1);
            background-color: #ffffff;
        }
        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 6px;
        }
        .btn-submit-reg {
            background: var(--navy-900);
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 6px;
            border: 1px solid var(--navy-950);
            width: 100%;
            transition: all 0.2s ease;
            cursor: pointer;
            letter-spacing: 0.2px;
        }
        .btn-submit-reg:hover {
            background: var(--navy-800);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(10, 20, 38, 0.25);
        }

        /* Status Check Widget */
        .status-check-card {
            background: var(--cream-50);
            border: 1px solid var(--neutral-200);
            border-radius: 6px;
            padding: 22px 24px;
            margin-top: 35px;
        }

        /* Floating Sticky Mobile CTA */
        .mobile-floating-cta {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            padding: 10px 16px;
            box-shadow: 0 -2px 12px rgba(0,0,0,0.1);
            z-index: 1050;
            border-top: 1px solid var(--neutral-200);
        }
        .mobile-floating-cta-inner {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .mobile-cta-btn-apply {
            flex: 2;
            background: var(--navy-900);
            color: #ffffff !important;
            font-weight: 700;
            padding: 11px 16px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
        }
        .mobile-cta-btn-wa {
            flex: 1;
            background: #16a34a;
            color: #ffffff !important;
            font-weight: 600;
            padding: 11px 14px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Footer — Institutional Colophon */
        .landing-footer {
            background: var(--navy-950);
            color: #94a3b8;
            padding: 50px 0 28px 0;
            font-size: 13.5px;
            margin-top: 60px;
            border-top: 3px solid var(--gold-500);
        }
        .landing-footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s;
        }
        .landing-footer a:hover {
            color: #ffffff;
        }

        @media (max-width: 991px) {
            .stat-item {
                border-right: none;
                border-bottom: 1px solid var(--neutral-200);
                padding: 12px 0;
            }
            .stat-item:last-child {
                border-bottom: none;
            }
        }

        @media (max-width: 768px) {
            .hero-section { padding: 40px 0 60px 0; }
            .hero-title { font-size: 30px; }
            .hero-subtitle { font-size: 15px; }
            .reg-section { padding: 24px 16px; margin-top: -15px; }
            .nav-links { display: none; }
            .section-padding { padding: 45px 0; }
            .section-title { font-size: 27px; }
            .award-card { padding: 24px 16px; margin-bottom: 16px; }
            .mobile-floating-cta { display: block; }
            .countdown-container { justify-content: center; }
            .btn-hero-primary, .btn-hero-outline { width: 100%; justify-content: center; }
        }
    </style>
    <script>
    function toggleSchoolInput(val) {
        var box = document.getElementById('other_school_box');
        var inp = document.getElementById('other_school_name');
        var lblInternal = document.getElementById('lbl_internal');
        var lblExternal = document.getElementById('lbl_external');

        if (lblInternal && lblExternal) {
            if (val === 'external') {
                lblExternal.classList.add('active');
                lblInternal.classList.remove('active');
            } else {
                lblInternal.classList.add('active');
                lblExternal.classList.remove('active');
            }
        }

        if (box && inp) {
            if (val === 'external') {
                box.style.display = 'block';
                inp.required = true;
                inp.focus();
            } else {
                box.style.display = 'none';
                inp.required = false;
                inp.value = '';
            }
        }
    }

    function switchSyllabusTab(tabId, btn) {
        var tables = document.querySelectorAll('.syllabus-class-table');
        var btns = document.querySelectorAll('.syllabus-tab-btn');
        btns.forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');

        var rows = document.querySelectorAll('.syllabus-row');
        if (tabId === 'all') {
            rows.forEach(function(r) { r.style.display = ''; });
        } else {
            rows.forEach(function(r) {
                if (r.getAttribute('data-class') === tabId) {
                    r.style.display = '';
                } else {
                    r.style.display = 'none';
                }
            });
        }
    }

    function toggleFaq(element) {
        element.parentElement.classList.toggle('open');
    }

    // Countdown Timer Logic
    function startExamCountdown() {
        var examDate = new Date('Nov 15, 2026 09:30:00').getTime();
        var timer = setInterval(function() {
            var now = new Date().getTime();
            var distance = examDate - now;

            if (distance < 0) {
                clearInterval(timer);
                var el = document.getElementById('exam_countdown');
                if (el) el.innerHTML = '<span class="text-warning">Examination in Progress / Completed</span>';
                return;
            }

            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            var elDays = document.getElementById('cd_days');
            var elHours = document.getElementById('cd_hours');
            var elMins = document.getElementById('cd_mins');
            var elSecs = document.getElementById('cd_secs');

            if (elDays) elDays.innerText = (days < 10 ? '0' : '') + days;
            if (elHours) elHours.innerText = (hours < 10 ? '0' : '') + hours;
            if (elMins) elMins.innerText = (minutes < 10 ? '0' : '') + minutes;
            if (elSecs) elSecs.innerText = (seconds < 10 ? '0' : '') + seconds;
        }, 1000);
    }
    document.addEventListener('DOMContentLoaded', startExamCountdown);
    </script>
</head>
<body>

<?php
$fields_map = array();
if (!empty($field_settings)) {
    foreach ($field_settings as $fs) {
        $fields_map[$fs['field_name']] = $fs;
    }
}
function isFieldVisible($key, $map) {
    return (isset($map[$key]) && $map[$key]['is_visible'] == 1);
}
function isFieldRequired($key, $map) {
    return (isset($map[$key]) && $map[$key]['is_required'] == 1);
}
?>

<!-- Top Notice Ribbon -->
<div class="top-notification-strip">
    <div class="container text-center">
        <span class="notice-badge">Official Notification</span>
        <span>Sunrise Scholarship Cum Admission Test (SSAT-2026) &bull; Registrations Open &bull; 100% Free Entry</span>
        <a href="#register"><i class="fa fa-pencil-square-o"></i> Register Candidate</a>
    </div>
</div>

<!-- 1. Top Navigation Bar -->
<header class="landing-nav">
    <div class="container">
        <div class="row" style="display: flex; align-items: center;">
            <div class="col-xs-8 col-sm-6">
                <a href="<?php echo base_url(); ?>" class="school-brand">
                    <?php if (!empty($sch_setting[0]['image'])) { ?>
                        <img src="<?php echo base_url('uploads/school_content/logo/' . $sch_setting[0]['image']); ?>" alt="Logo" style="height: 44px; max-width: 140px; object-fit: contain;">
                    <?php } else { ?>
                        <div class="school-brand-icon">
                            <i class="fa fa-university"></i>
                        </div>
                    <?php } ?>
                    <div class="school-brand-text">
                        <h4><?php echo htmlspecialchars($sch_setting[0]['name'] ?: 'Sunrise International Public School'); ?></h4>
                        <span>Scholarship & Olympiad Wing</span>
                    </div>
                </a>
            </div>
            <div class="col-xs-4 col-sm-6 text-right">
                <div class="nav-links">
                    <a href="#about"><i class="fa fa-info-circle"></i> About</a>
                    <a href="#awards"><i class="fa fa-trophy"></i> Awards</a>
                    <a href="#syllabus"><i class="fa fa-book"></i> Syllabus</a>
                    <a href="#dates"><i class="fa fa-calendar"></i> Important Dates</a>
                    <a href="<?php echo site_url('admin/scholarshipexam/brochure/' . (isset($selected_exam['id']) ? $selected_exam['id'] : 1)); ?>" target="_blank">
                        <i class="fa fa-file-text-o"></i> Brochure
                    </a>
                    <a href="#register" class="nav-btn-apply"><i class="fa fa-pencil"></i> Apply Now</a>
                </div>
                <div class="visible-xs">
                    <a href="#register" class="nav-btn-apply" style="padding: 6px 14px; font-size: 13px;">Apply</a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- 2. Hero Section -->
<section id="about" class="hero-section">
    <div class="container">
        <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
            <div class="col-md-7 col-sm-12">
                <div class="hero-badge">
                    <i class="fa fa-shield"></i> Academic Year 2026-27 &bull; Classes 5th to 11th
                </div>
                <h1 class="hero-title">
                    Sunrise Scholarship <span class="title-accent">&amp;</span> Admission Test 2026
                </h1>
                <p class="hero-subtitle">
                    A merit-based scholarship examination recognizing intellectual excellence with <strong>up to 100% school fee waiver</strong>, substantial cash honors, and priority academic admission.
                </p>

                <ul class="hero-highlights-list">
                    <li><i class="fa fa-check"></i> Open to students of Classes 5th to 11th from all recognized schools</li>
                    <li><i class="fa fa-check"></i> Standard bilingual question paper (Hindi &amp; English)</li>
                    <li><i class="fa fa-check"></i> 100% NCERT curriculum blueprint &amp; OMR sheet examination</li>
                    <li><i class="fa fa-check"></i> Meritorious cash awards, mementos &amp; certified credentials</li>
                </ul>

                <div class="hero-cta-group">
                    <a href="#register" class="btn-hero-primary">
                        <i class="fa fa-pencil"></i> Register Online (Free)
                    </a>
                    <a href="<?php echo site_url('admin/scholarshipexam/brochure/' . (isset($selected_exam['id']) ? $selected_exam['id'] : 1)); ?>" target="_blank" class="btn-hero-outline">
                        <i class="fa fa-download"></i> Download Official Brochure
                    </a>
                </div>

                <!-- Live Countdown Timer -->
                <div class="countdown-container" id="exam_countdown">
                    <div class="countdown-title">
                        <i class="fa fa-clock-o"></i> Examination Date: 15 Nov 2026
                    </div>
                    <div class="countdown-boxes">
                        <div class="count-box">
                            <div class="count-num" id="cd_days">00</div>
                            <div class="count-label">Days</div>
                        </div>
                        <div class="count-box">
                            <div class="count-num" id="cd_hours">00</div>
                            <div class="count-label">Hours</div>
                        </div>
                        <div class="count-box">
                            <div class="count-num" id="cd_mins">00</div>
                            <div class="count-label">Mins</div>
                        </div>
                        <div class="count-box">
                            <div class="count-num" id="cd_secs">00</div>
                            <div class="count-label">Secs</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-sm-12" style="margin-top: 25px;">
                <div class="hero-glance-card">
                    <div class="hero-glance-heading">
                        <span>Examination Summary</span>
                        <span class="official-seal-tag">Official</span>
                    </div>

                    <div class="glance-item">
                        <div class="glance-icon blue"><i class="fa fa-calendar"></i></div>
                        <div>
                            <div class="glance-label">Date of Examination</div>
                            <div class="glance-val">15 November 2026 (Sunday)</div>
                        </div>
                    </div>

                    <div class="glance-item">
                        <div class="glance-icon amber"><i class="fa fa-file-text-o"></i></div>
                        <div>
                            <div class="glance-label">Format &amp; Duration</div>
                            <div class="glance-val">90 Minutes &bull; OMR Multiple Choice</div>
                        </div>
                    </div>

                    <div class="glance-item">
                        <div class="glance-icon green"><i class="fa fa-users"></i></div>
                        <div>
                            <div class="glance-label">Eligibility Criteria</div>
                            <div class="glance-val">Classes 5th to 11th (Any recognized school)</div>
                        </div>
                    </div>

                    <div class="glance-item">
                        <div class="glance-icon purple"><i class="fa fa-trophy"></i></div>
                        <div>
                            <div class="glance-label">Scholarship Concession</div>
                            <div class="glance-val">Up to 100% Fee Concession + Cash Honors</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter Bar -->
<section class="stats-bar-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-6 stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Tuition Fee Waiver</div>
            </div>
            <div class="col-md-3 col-xs-6 stat-item">
                <div class="stat-number">Classes 5–11</div>
                <div class="stat-label">Eligible Students</div>
            </div>
            <div class="col-md-3 col-xs-6 stat-item">
                <div class="stat-number">₹ Cash Honors</div>
                <div class="stat-label">Merit Cash Prizes</div>
            </div>
            <div class="col-md-3 col-xs-6 stat-item">
                <div class="stat-number">Free</div>
                <div class="stat-label">No Registration Fee</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Awards & Recognitions Section -->
<section id="awards" class="section-padding" style="background: #ffffff;">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="fa fa-trophy"></i> Rewarding Excellence</span>
            <h2 class="section-title">Scholarships & Honors</h2>
            <p class="section-subtitle">Top achievers in the Sunrise Scholarship Test receive substantial financial fee concessions, recognition, and future academic honors.</p>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="award-card card-gold">
                    <div class="award-icon-box gold"><i class="fa fa-graduation-cap"></i></div>
                    <h4>Up to 100% Scholarship</h4>
                    <p>Complete or substantial tuition fee waivers across school academic fees for top rankers throughout the academic session.</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="award-card card-cash">
                    <div class="award-icon-box cash"><i class="fa fa-money"></i></div>
                    <h4>Thousands in Cash Awards</h4>
                    <p>Substantial cash prizes presented to district and state high-scorers in recognition of academic brilliance.</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-12" style="margin-bottom: 25px;">
                <div class="award-card card-cert">
                    <div class="award-icon-box cert"><i class="fa fa-certificate"></i></div>
                    <h4>Mementos & Certificates</h4>
                    <p>Prestigious mementos, medals, and participation certificates for all qualified candidates to boost their future credentials.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Steps to Apply Section -->
<section class="section-padding" style="background: #ffffff; border-top: 1px solid var(--slate-100);">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="fa fa-list-ol"></i> Simple 4-Step Process</span>
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Getting your scholarship and official roll number takes less than 2 minutes.</p>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5>Fill Free Form</h5>
                    <p>Enter student details, class, mobile number, and current school name below.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5>Get Roll Slip</h5>
                    <p>Instant confirmation with your unique Roll Number generated on submission.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5>Take Test</h5>
                    <p>Appear on Sunday, 15 Nov 2026 for 90-min OMR multiple-choice examination.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h5>Win Scholarship</h5>
                    <p>Results on 21 Nov 2026 with up to 100% scholarship awards distribution.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Syllabus, Questions & Pattern Matrix -->
<section id="syllabus" class="section-padding" style="background: var(--slate-50);">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="fa fa-book"></i> Examination Scheme</span>
            <h2 class="section-title">Syllabus & Question Blueprint</h2>
            <p class="section-subtitle">Prepared strictly in accordance with 100% NCERT curriculum. Bilingual questions in Hindi & English.</p>
        </div>

        <div class="syllabus-container">
            <!-- Filter Tabs -->
            <div class="syllabus-tabs">
                <button type="button" class="syllabus-tab-btn active" onclick="switchSyllabusTab('all', this)"><i class="fa fa-th"></i> All Classes</button>
                <button type="button" class="syllabus-tab-btn" onclick="switchSyllabusTab('c5', this)">Class 5th</button>
                <button type="button" class="syllabus-tab-btn" onclick="switchSyllabusTab('c6_10', this)">Classes 6th - 10th</button>
                <button type="button" class="syllabus-tab-btn" onclick="switchSyllabusTab('c11_sci', this)">Class 11th Science</button>
                <button type="button" class="syllabus-tab-btn" onclick="switchSyllabusTab('c11_arts', this)">Class 11th Arts</button>
            </div>

            <div class="syllabus-table-wrapper">
                <table class="table custom-syllabus-table">
                    <thead>
                        <tr>
                            <th style="width: 18%;">Target Class</th>
                            <th style="width: 44%;">Subject Curriculum (NCERT Standard)</th>
                            <th style="width: 12%;">Questions</th>
                            <th style="width: 12%;">Total Marks</th>
                            <th style="width: 14%;">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="syllabus-row" data-class="c5">
                            <td><span class="class-pill">Class 5<sup>th</sup></span></td>
                            <td><strong>Hindi, English, Mathematics, EVS</strong></td>
                            <td class="text-center"><strong>40</strong></td>
                            <td class="text-center"><span class="badge" style="background:#10b981; font-size:13px; font-weight:700;">50 Marks</span></td>
                            <td class="text-center">90 Minutes</td>
                        </tr>
                        <tr class="syllabus-row" data-class="c6_10">
                            <td><span class="class-pill">Class 6<sup>th</sup> to 10<sup>th</sup></span></td>
                            <td><strong>Hindi, English, Science, Mathematics, Social Studies, Logical Reasoning</strong></td>
                            <td class="text-center"><strong>60</strong></td>
                            <td class="text-center"><span class="badge" style="background:#10b981; font-size:13px; font-weight:700;">90 Marks</span></td>
                            <td class="text-center">90 Minutes</td>
                        </tr>
                        <tr class="syllabus-row" data-class="c11_sci">
                            <td><span class="class-pill">Class 11<sup>th</sup> Science</span></td>
                            <td><strong>English, Mathematics / Biology, Physics, Chemistry</strong></td>
                            <td class="text-center"><strong>60</strong></td>
                            <td class="text-center"><span class="badge" style="background:#10b981; font-size:13px; font-weight:700;">120 Marks</span></td>
                            <td class="text-center">90 Minutes</td>
                        </tr>
                        <tr class="syllabus-row" data-class="c11_arts">
                            <td><span class="class-pill">Class 11<sup>th</sup> Arts</span></td>
                            <td><strong>English, Political Science, History, Geography</strong></td>
                            <td class="text-center"><strong>60</strong></td>
                            <td class="text-center"><span class="badge" style="background:#10b981; font-size:13px; font-weight:700;">120 Marks</span></td>
                            <td class="text-center">90 Minutes</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="background: #ffffff; padding: 16px 22px; font-size: 13.5px; color: #64748b; border-top: 1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div><i class="fa fa-info-circle text-primary"></i> <strong>Note:</strong> Negative marking guidelines and complete question schemes are detailed in the prospectus brochure.</div>
                <div><a href="<?php echo site_url('admin/scholarshipexam/brochure/' . (isset($selected_exam['id']) ? $selected_exam['id'] : 1)); ?>" target="_blank" style="font-weight:700; color:#2563eb;"><i class="fa fa-external-link"></i> Download Official Question Blueprint</a></div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Important Dates Section -->
<section id="dates" class="section-padding" style="background: #ffffff;">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Timeline</span>
            <h2 class="section-title">Important Dates & Schedule</h2>
            <p class="section-subtitle">Mark your calendar and ensure completion of your application prior to the cut-off date.</p>
        </div>

        <div class="dates-grid">
            <div class="date-box">
                <div class="date-box-label">Notification Release</div>
                <div class="date-box-val">15 Oct 2026</div>
            </div>
            <div class="date-box">
                <div class="date-box-label">Registrations Start</div>
                <div class="date-box-val">15 Oct 2026</div>
            </div>
            <div class="date-box highlight">
                <div class="date-box-label text-danger">Registrations Close</div>
                <div class="date-box-val text-danger">10 Nov 2026</div>
            </div>
            <div class="date-box">
                <div class="date-box-label">Admit Card Release</div>
                <div class="date-box-val">12 Nov 2026</div>
            </div>
            <div class="date-box highlight" style="background: #eff6ff; border-top-color: #2563eb;">
                <div class="date-box-label" style="color:#1d4ed8;">Examination Day</div>
                <div class="date-box-val" style="color:#1e3a8a;">15 Nov 2026</div>
            </div>
            <div class="date-box">
                <div class="date-box-label">Result Announcement</div>
                <div class="date-box-val">21 Nov 2026</div>
            </div>
        </div>
    </div>
</section>

<!-- 6. Alternative Registration Modes (WhatsApp & Offline) -->
<section class="container" style="margin-top: 20px;">
    <div class="alt-methods-banner">
        <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
            <div class="col-md-8 col-sm-12">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="font-size: 38px; color: #16a34a;"><i class="fa fa-whatsapp"></i></div>
                    <div>
                        <h4 style="margin: 0 0 5px 0; font-weight: 800; color: #14532d;">Register Easily via WhatsApp or at School Reception</h4>
                        <p style="margin: 0; font-size: 14px; color: #166534;">
                            Unable to fill the online form? Simply send <strong>Student Name, Father's Name, Present Class, School Name, Village & District</strong> via WhatsApp to our official helpline.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 text-right" style="margin-top: 15px;">
                <a href="https://wa.me/919783200821?text=Hello%2C%20I%20want%20to%20register%20for%20Sunrise%20Scholarship%20Cum%20Admission%20Test%202026." target="_blank" class="whatsapp-btn">
                    <i class="fa fa-whatsapp"></i> WhatsApp 9783200821
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 7. Registration Form Anchor Section -->
<section id="register" class="container" style="margin-bottom: 70px;">
    <div class="reg-section">
        <div class="reg-section-header">
            <span class="section-badge" style="background:#eff6ff; color:#1d4ed8;">Free Registration</span>
            <h2>Candidate Registration Form</h2>
            <p style="color: #64748b; font-size: 15px; margin: 0;">Please provide accurate candidate and contact details. Roll Number & Admit Card will be generated using this data.</p>
        </div>

        <?php if (isset($registration_closed) && $registration_closed) { ?>
            <div class="alert alert-danger text-center" style="border-radius: 12px; padding: 30px;">
                <h3 style="margin-top: 0; font-weight: 800; color: #991b1b;"><i class="fa fa-ban"></i> Registrations Closed</h3>
                <p style="font-size: 15px; color: #7f1d1d; margin-top: 10px;">
                    Online candidate registration for <strong><?php echo htmlspecialchars($selected_exam['title']); ?></strong> has closed.
                    <?php echo (!empty($selected_exam['registration_stopped_at'])) ? 'Official closure time: <strong>' . date('d M Y h:i A', strtotime($selected_exam['registration_stopped_at'])) . '</strong>' : ''; ?>
                </p>
            </div>
        <?php } else { ?>

            <form action="<?php echo site_url('scholarshipregister/submit'); ?>" method="POST" enctype="multipart/form-data">
                <?php if (!empty($selected_exam) && isset($is_direct_link) && $is_direct_link) { ?>
                    <input type="hidden" name="exam_id" value="<?php echo $selected_exam['id']; ?>">
                <?php } else { ?>
                    <div class="form-group" style="margin-bottom: 22px;">
                        <label>Target Examination / Competition *</label>
                        <select name="exam_id" class="form-control" onchange="location.href='<?php echo site_url('scholarshipregister/apply/'); ?>' + this.value" required>
                            <?php if (!empty($exams)) {
                                foreach ($exams as $e) { ?>
                                    <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($e['title']); ?> (<?php echo htmlspecialchars($e['exam_code']); ?>) - <?php echo ($e['is_paid'] == 1) ? '$' . number_format($e['registration_fee'], 2) : 'FREE'; ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Candidate Full Name *</label>
                            <input type="text" name="candidate_name" class="form-control" placeholder="Enter student full name" required>
                        </div>
                    </div>
                    <?php if (isFieldVisible('school_name', $fields_map)) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Present School <?php echo isFieldRequired('school_name', $fields_map) ? '*' : ''; ?></label>
                                <div class="school-toggle-group">
                                    <label class="school-toggle-label active" id="lbl_internal">
                                        <input type="radio" name="school_type" value="internal" checked onchange="toggleSchoolInput('internal')" onclick="toggleSchoolInput('internal')">
                                        <i class="fa fa-university"></i> <?php echo htmlspecialchars($sch_setting[0]['name'] ?: 'Sunrise School'); ?>
                                    </label>
                                    <label class="school-toggle-label" id="lbl_external">
                                        <input type="radio" name="school_type" value="external" onchange="toggleSchoolInput('external')" onclick="toggleSchoolInput('external')">
                                        <i class="fa fa-building-o"></i> Other School (Eligible)
                                    </label>
                                </div>
                                <div id="other_school_box" style="display: none; margin-top: 10px;">
                                    <input type="text" name="other_school_name" id="other_school_name" class="form-control" placeholder="Type candidate's current school name">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mobile Number (For WhatsApp / SMS alerts) *</label>
                            <input type="text" name="mobile" class="form-control" placeholder="10-digit primary mobile number" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="candidate@example.com">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Present Class / Grade *</label>
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Select Class --</option>
                                <?php foreach ($classList as $c) { ?>
                                    <option value="<?php echo $c['id']; ?>">Class <?php echo htmlspecialchars($c['class']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <?php if (isFieldVisible('father_name', $fields_map)) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Father's Name <?php echo isFieldRequired('father_name', $fields_map) ? '*' : ''; ?></label>
                                <input type="text" name="father_name" class="form-control" placeholder="Father's full name" <?php echo isFieldRequired('father_name', $fields_map) ? 'required' : ''; ?>>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <?php if (isFieldVisible('mother_name', $fields_map)) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mother's Name <?php echo isFieldRequired('mother_name', $fields_map) ? '*' : ''; ?></label>
                                <input type="text" name="mother_name" class="form-control" placeholder="Mother's full name" <?php echo isFieldRequired('mother_name', $fields_map) ? 'required' : ''; ?>>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (isFieldVisible('dob', $fields_map)) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date of Birth <?php echo isFieldRequired('dob', $fields_map) ? '*' : ''; ?></label>
                                <input type="date" name="dob" class="form-control" <?php echo isFieldRequired('dob', $fields_map) ? 'required' : ''; ?>>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <?php if (isFieldVisible('gender', $fields_map)) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender <?php echo isFieldRequired('gender', $fields_map) ? '*' : ''; ?></label>
                                <select name="gender" class="form-control" <?php echo isFieldRequired('gender', $fields_map) ? 'required' : ''; ?>>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (isFieldVisible('category', $fields_map)) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Student Category <?php echo isFieldRequired('category', $fields_map) ? '*' : ''; ?></label>
                                <select name="category" class="form-control" <?php echo isFieldRequired('category', $fields_map) ? 'required' : ''; ?>>
                                    <option value="General">General</option>
                                    <option value="OBC">OBC</option>
                                    <option value="SC">SC</option>
                                    <option value="ST">ST</option>
                                    <option value="SBC">SBC</option>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <?php if (isFieldVisible('village', $fields_map)) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Village / Town <?php echo isFieldRequired('village', $fields_map) ? '*' : ''; ?></label>
                                <input type="text" name="village" class="form-control" placeholder="Village / Town" <?php echo isFieldRequired('village', $fields_map) ? 'required' : ''; ?>>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (isFieldVisible('city', $fields_map)) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City / District <?php echo isFieldRequired('city', $fields_map) ? '*' : ''; ?></label>
                                <input type="text" name="city" class="form-control" placeholder="District name" <?php echo isFieldRequired('city', $fields_map) ? 'required' : ''; ?>>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <?php if (isFieldVisible('address', $fields_map)) { ?>
                    <div class="form-group">
                        <label>Residential Address <?php echo isFieldRequired('address', $fields_map) ? '*' : ''; ?></label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Full address" <?php echo isFieldRequired('address', $fields_map) ? 'required' : ''; ?>></textarea>
                    </div>
                <?php } ?>

                <div style="margin-top: 25px;">
                    <button type="submit" class="btn-submit-reg">
                        <i class="fa fa-check-circle"></i> Submit Application & Generate Registration Slip
                    </button>
                </div>
            </form>
        <?php } ?>

        <!-- Check Existing Candidate Status Widget -->
        <div class="status-check-card">
            <h4 style="font-weight: 800; color: #1e40af; margin-top: 0; font-size: 16px;">
                <i class="fa fa-search"></i> Already Registered? Check Admit Card & Result Status
            </h4>
            <p class="text-muted" style="font-size: 13px; margin-bottom: 12px;">Enter your Roll Number or Mobile Number below to download Admit Card or view your published rank/result.</p>
            <form action="<?php echo site_url('scholarshipregister/check_status'); ?>" method="POST" class="row">
                <div class="col-sm-8 col-xs-12" style="margin-bottom: 8px;">
                    <input type="text" name="search_term" class="form-control" placeholder="Enter Roll Number (e.g. SSAT-1001) or Mobile Number" required>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block" style="border-radius: 50px; font-weight: 700; padding: 12px 18px;">
                        <i class="fa fa-search"></i> Check Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Frequently Asked Questions (FAQ) Section -->
<section class="section-padding" style="background: #ffffff; border-top: 1px solid var(--slate-200);">
    <div class="container" style="max-width: 860px;">
        <div class="section-header">
            <span class="section-badge"><i class="fa fa-question-circle"></i> Got Questions?</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Find answers to common queries regarding eligibility, test format, and awards.</p>
        </div>

        <div class="faq-card open">
            <div class="faq-header" onclick="toggleFaq(this)">
                <span>Who is eligible to participate in SSAT-2026?</span>
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="faq-body">
                Students presently studying in Classes 5th to 11th in any recognized school (CBSE, RBSE, ICSE or State Boards) are eligible to participate. Both English and Hindi medium students are welcomed.
            </div>
        </div>

        <div class="faq-card">
            <div class="faq-header" onclick="toggleFaq(this)">
                <span>What is the examination pattern and syllabus?</span>
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="faq-body">
                The test is 100% based on the NCERT syllabus of the candidate's respective class. It consists of multiple-choice questions (MCQs) answered on an OMR sheet over 90 minutes. Bilingual test papers are provided.
            </div>
        </div>

        <div class="faq-card">
            <div class="faq-header" onclick="toggleFaq(this)">
                <span>Is there any registration fee?</span>
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="faq-body">
                Online registration for SSAT-2026 is completely <strong>FREE</strong> of cost! You can register online, via WhatsApp helpline (+91 9783200821), or in-person at the school reception.
            </div>
        </div>

        <div class="faq-card">
            <div class="faq-header" onclick="toggleFaq(this)">
                <span>How will the scholarship awards be distributed?</span>
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="faq-body">
                Top meritorious rankers will receive up to 100% concession on tuition fees for the 2026-27 session, along with cash awards, mementos, medals, and official certificates of excellence.
            </div>
        </div>
    </div>
</section>

<!-- Floating Sticky Mobile CTA Bar -->
<div class="mobile-floating-cta">
    <div class="mobile-floating-cta-inner">
        <a href="#register" class="mobile-cta-btn-apply">
            <i class="fa fa-pencil"></i> Apply Now (Free)
        </a>
        <a href="https://wa.me/919783200821?text=Hello%2C%20I%20want%20to%20register%20for%20Sunrise%20Scholarship%20Test%202026." target="_blank" class="mobile-cta-btn-wa">
            <i class="fa fa-whatsapp"></i> Chat
        </a>
    </div>
</div>

<!-- 8. Footer -->
<footer class="landing-footer">
    <div class="container text-center">
        <div style="font-size: 22px; font-weight: 800; color: #ffffff; margin-bottom: 8px;">
            <?php echo htmlspecialchars($sch_setting[0]['name'] ?: 'Sunrise International Public School'); ?>
        </div>
        <p style="margin-bottom: 22px; color: #94a3b8;">Affiliated with CBSE &bull; Empowering Young Leaders Through Academic Brilliance</p>
        <div style="margin-bottom: 22px; display:flex; justify-content:center; gap:25px; flex-wrap:wrap; font-size:14px;">
            <span><i class="fa fa-phone text-warning"></i> Helpline: +91 9783200821</span>
            <span><i class="fa fa-envelope text-info"></i> admission@sunriseschool.in</span>
            <span><i class="fa fa-map-marker text-danger"></i> Main Campus, Sunrise School</span>
        </div>
        <div style="border-top: 1px solid #1e293b; padding-top: 20px; font-size: 13px; color: #64748b;">
            &copy; <?php echo date('Y'); ?> Sunrise International Public School. All rights reserved.
        </div>
    </div>
</footer>

</body>
</html>
