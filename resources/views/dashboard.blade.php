<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Panic Button — Gintara Net</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            --primary-blue: #0066cc;
            --secondary-blue: #4d94ff;
            --light-blue: #e6f0ff;
            --soft-blue: #f0f7ff;
            --pure-white: #ffffff;
            --off-white: #f8fafc;
            --dark-blue: #003d99;
            --accent-blue: #1a75ff;
            --text-primary: #1a2634;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --border-light: #e2e8f0;
            --shadow-sm: 0 4px 6px rgba(0, 102, 204, .1);
            --shadow-md: 0 8px 20px rgba(0, 102, 204, .15);
            --gradient-blue: linear-gradient(135deg, #0066cc, #4d94ff);
            --red: #e63946;
            --amber: #f4a261;
            --green: #2dc653;
            --yellow: #ffd966;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--soft-blue);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(circle at 10% 20%, rgba(0, 102, 204, .03) 0%, transparent 30%),
                radial-gradient(circle at 90% 80%, rgba(77, 148, 255, .03) 0%, transparent 30%);
            pointer-events: none;
        }

        .wrap {
            max-width: 1360px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--pure-white);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 14px 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            position: relative;
            z-index: 9999;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand img {
            height: 38px;
        }

        .brand-name {
            font-family: 'Rajdhani', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: 1px;
        }

        .brand-name span {
            color: var(--primary-blue);
        }

        .profile-area {
            position: relative;
            z-index: 9999;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: 50px;
            background: var(--light-blue);
            border: 1px solid var(--border-light);
            cursor: pointer;
            transition: all .2s;
        }

        .profile-btn:hover {
            border-color: var(--primary-blue);
            background: var(--soft-blue);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: white;
        }

        .profile-name {
            font-weight: 600;
            font-size: 15px;
            color: var(--text-primary);
        }

        .profile-guid {
            font-size: 11px;
            color: var(--text-muted);
            font-family: monospace;
            letter-spacing: .5px;
        }

        .dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 260px;
            background: var(--pure-white);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            display: none;
            z-index: 9999;
        }

        .dropdown.open {
            display: block;
            animation: slideDown .2s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dd-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--border-light);
        }

        .dd-head .dname {
            font-weight: 700;
            font-size: 15px;
            color: var(--text-primary);
        }

        .dd-head .demail {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .dd-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 18px;
            cursor: pointer;
            transition: .2s;
        }

        .dd-item:hover {
            background: var(--light-blue);
        }

        .dd-item .di-icon {
            font-size: 20px;
            width: 28px;
            text-align: center;
            color: var(--primary-blue);
        }

        .dd-item .di-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
        }

        .dd-item .di-sub {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ── SESSION ALERT — auto-dismiss ── */
        .session-alert {
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 18px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 1;
            transition: opacity .6s ease;
        }

        .session-alert.hide {
            opacity: 0;
            pointer-events: none;
        }

        .session-alert.success {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            color: var(--green);
        }

        .session-alert.error {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            color: var(--red);
        }

        /* ── GRID ── */
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: stretch;
            position: relative;
            z-index: 1;
        }

        .col {
            display: flex;
            flex-direction: column;
            gap: 20px;
            height: 100%;
        }

        /* ── CARD ── */
        .card {
            background: var(--pure-white);
            border: 1px solid var(--border-light);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 400px;
            position: relative;
            z-index: 1;
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-head {
            font-family: 'Rajdhani', sans-serif;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: .5px;
            color: var(--primary-blue);
            padding-bottom: 14px;
            border-bottom: 2px solid var(--light-blue);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-head .ch-icon {
            font-size: 20px;
            color: var(--primary-blue);
        }

        /* ── PANIC BUTTON ── */
        .panic-wrap {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 32px;
            padding: 16px 8px;
            flex: 1;
        }

        .panic-info-text {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 240px;
        }

        .panic-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panic-title i {
            color: var(--primary-blue);
            font-size: 24px;
        }

        .panic-description {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .panic-ring {
            position: relative;
            width: 240px;
            height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .panic-ring::before,
        .panic-ring::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(230, 57, 70, .3);
            animation: ripple 2.5s ease-out infinite;
        }

        .panic-ring::before {
            width: 100%;
            height: 100%;
            animation-delay: 0s;
        }

        .panic-ring::after {
            width: 85%;
            height: 85%;
            animation-delay: .8s;
        }

        @keyframes ripple {
            0% {
                opacity: .6;
                transform: scale(.95);
            }

            100% {
                opacity: 0;
                transform: scale(1.1);
            }
        }

        .panic-ring.active::before,
        .panic-ring.active::after {
            border-color: rgba(244, 162, 97, .3);
        }

        .panic-btn {
            position: relative;
            z-index: 2;
            width: 190px;
            height: 190px;
            border-radius: 50%;
            background: radial-gradient(circle at 40% 35%, #ff4455, #b50012);
            border: none;
            box-shadow: 0 0 0 6px rgba(230, 57, 70, .15), 0 8px 30px rgba(230, 57, 70, .45), inset 0 -4px 12px rgba(0, 0, 0, .3);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            transition: all .25s;
            user-select: none;
        }

        .panic-btn:hover:not(:disabled) {
            transform: scale(1.04);
            box-shadow: 0 0 0 8px rgba(230, 57, 70, .2), 0 12px 40px rgba(230, 57, 70, .6), inset 0 -4px 12px rgba(0, 0, 0, .3);
        }

        .panic-btn:active:not(:disabled) {
            transform: scale(.97);
        }

        .panic-btn:disabled {
            opacity: .75;
            cursor: not-allowed;
        }

        .pb-icon {
            font-size: 60px;
            line-height: 1;
        }

        .pb-icon i {
            color: var(--yellow);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, .3));
        }

        .pb-label {
            font-family: 'Rajdhani', sans-serif;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #fff;
        }

        .pb-sub {
            font-size: 13px;
            color: rgba(255, 255, 255, .75);
            letter-spacing: 1px;
        }

        .panic-btn.darurat {
            background: radial-gradient(circle at 40% 35%, #f4a261, #c9530a);
            box-shadow: 0 0 0 6px rgba(244, 162, 97, .15), 0 8px 30px rgba(244, 162, 97, .45), inset 0 -4px 12px rgba(0, 0, 0, .3);
        }

        .panic-status {
            margin-top: 16px;
            padding: 8px 22px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-aman {
            background: rgba(45, 198, 83, .15);
            border: 1px solid rgba(45, 198, 83, .3);
            color: var(--green);
        }

        .status-darurat {
            background: rgba(244, 162, 97, .15);
            border: 1px solid rgba(244, 162, 97, .3);
            color: var(--amber);
            animation: blink 1.2s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        /* ── TIMELINE ── */
        .timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
            flex: 1;
            overflow-y: auto;
            max-height: 400px;
            padding-right: 4px;
        }

        .timeline::-webkit-scrollbar {
            width: 5px;
        }

        .timeline::-webkit-scrollbar-track {
            background: var(--light-blue);
            border-radius: 10px;
        }

        .timeline::-webkit-scrollbar-thumb {
            background: var(--primary-blue);
            border-radius: 10px;
        }

        .tl-item {
            display: flex;
            gap: 14px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-light);
            animation: fadeSlide .3s ease;
            transition: background .2s;
        }

        .tl-item:hover {
            background: var(--light-blue);
            border-radius: 8px;
            padding-left: 8px;
            padding-right: 8px;
        }

        .tl-item:last-child {
            border-bottom: none;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .tl-icon-wrap {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .tl-alarm {
            background: rgba(230, 57, 70, .18);
            color: var(--red);
        }

        .tl-proses {
            background: rgba(74, 163, 255, .18);
            color: var(--primary-blue);
        }

        .tl-selesai {
            background: rgba(45, 198, 83, .18);
            color: var(--green);
        }

        .tl-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
        }

        .tl-time {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .tl-extra {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 3px;
            font-family: monospace;
        }

        .tl-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 4px;
        }

        .badge-menunggu {
            background: rgba(244, 162, 97, .15);
            color: var(--amber);
            border: 1px solid rgba(244, 162, 97, .25);
        }

        .badge-diproses {
            background: rgba(74, 163, 255, .15);
            color: var(--primary-blue);
            border: 1px solid rgba(74, 163, 255, .25);
        }

        .badge-selesai {
            background: rgba(45, 198, 83, .15);
            color: var(--green);
            border: 1px solid rgba(45, 198, 83, .25);
        }

        .tl-empty {
            text-align: center;
            padding: 32px 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .tl-empty .icon {
            font-size: 40px;
            margin-bottom: 8px;
        }

        /* ── MAP ── */
        #pbMap {
            height: 260px;
            width: 100%;
            flex: 1;
            border-radius: 12px;
            border: 1px solid var(--border-light);
            z-index: 10;
            position: relative;
            isolation: isolate;
            background: var(--off-white);
        }

        .leaflet-pane,
        .leaflet-top,
        .leaflet-bottom {
            z-index: 10 !important;
        }

        .leaflet-control {
            z-index: 20 !important;
        }

        .leaflet-container {
            background: var(--off-white) !important;
        }

        /* ── INFO KEAMANAN ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 4px;
            flex: 1;
            align-content: start;
        }

        .info-cell {
            background: var(--off-white);
            border-radius: 12px;
            padding: 14px 16px;
            border: 1px solid var(--border-light);
        }

        .ic-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .ic-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dot-aman {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
            animation: pulse2 2s infinite;
        }

        .dot-darurat {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--amber);
            animation: pulse2 .8s infinite;
        }

        @keyframes pulse2 {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .4;
            }
        }

        /* ── MODAL ── */
        .modal-bg {
            position: fixed;
            inset: 0;
            z-index: 10000;
            background: rgba(0, 0, 0, .5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-bg.open {
            display: flex;
        }

        .modal {
            background: var(--pure-white);
            border: 1px solid var(--border-light);
            border-radius: 22px;
            padding: 30px;
            max-height: 90vh;
            overflow-y: auto;
            animation: popIn .25s ease;
            position: relative;
            box-shadow: var(--shadow-md);
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(.94);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-close {
            position: absolute;
            top: 14px;
            right: 18px;
            font-size: 26px;
            color: var(--text-muted);
            cursor: pointer;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: var(--primary-blue);
        }

        .modal-footer-close {
            display: flex;
            justify-content: center;
            margin-top: 22px;
        }

        .btn-footer-close {
            padding: 12px 40px;
            border-radius: 50px;
            background: transparent;
            border: 2px solid var(--border-light);
            color: var(--text-primary);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 140px;
        }

        .btn-footer-close:hover {
            border-color: var(--primary-blue);
            background: var(--light-blue);
            color: var(--primary-blue);
        }

        /* ── PROFILE MODAL ── */
        #profileModal .modal {
            max-width: 960px;
            width: 100%;
        }

        .modal-h {
            margin-bottom: 22px;
            padding-right: 30px;
            text-align: center;
        }

        .modal-h h2 {
            font-family: 'Rajdhani', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .modal-h p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .data-section {
            background: var(--off-white);
            border-radius: 14px;
            padding: 20px;
            border: 1px solid var(--border-light);
        }

        .sec-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--primary-blue);
            border-bottom: 1px solid var(--border-light);
            padding-bottom: 10px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dr {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .dr:last-child {
            border-bottom: none;
        }

        .dl {
            width: 120px;
            font-size: 13px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
        }

        .dv {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            word-break: break-word;
            min-width: 0;
        }

        .fgroup {
            margin-bottom: 14px;
        }

        .fgroup label {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 5px;
            font-weight: 500;
        }

        /* ── PASSWORD TOGGLE ── */
        .pass-wrap {
            position: relative;
        }

        .finput {
            width: 100%;
            padding: 10px 13px;
            background: var(--pure-white);
            border: 1px solid var(--border-light);
            border-radius: 9px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: inherit;
            transition: .2s;
        }

        .pass-wrap .finput {
            padding-right: 42px;
        }

        .finput:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, .1);
        }

        .finput::placeholder {
            color: var(--text-muted);
            font-style: italic;
        }

        .pass-toggle {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 17px;
            display: flex;
            align-items: center;
            padding: 2px;
            transition: color .2s;
            line-height: 1;
        }

        .pass-toggle:hover {
            color: var(--primary-blue);
        }

        .fbtn {
            width: 100%;
            padding: 10px;
            background: var(--gradient-blue);
            border: none;
            border-radius: 9px;
            color: var(--pure-white);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            margin-top: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .fbtn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .form-note {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0 0 14px;
            line-height: 1.5;
            padding: 8px 10px;
            background: rgba(0, 102, 204, .05);
            border-radius: 8px;
            border-left: 3px solid var(--primary-blue);
        }

        .form-warning {
            background: rgba(244, 162, 97, .1);
            border: 1px solid rgba(244, 162, 97, .3);
            border-radius: 9px;
            padding: 10px 13px;
            margin-bottom: 14px;
        }

        .form-warning p {
            margin: 0;
            font-size: 12px;
            color: #92400e;
            line-height: 1.5;
        }

        .modal-section-divider {
            border: none;
            border-top: 2px solid var(--light-blue);
            margin: 22px 0;
        }

        /* ── PANIC MODAL ── */
        #panicModal .modal {
            max-width: 420px;
            width: 100%;
            text-align: center;
        }

        .pm-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .pm-icon i {
            color: var(--amber);
        }

        .pm-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .pm-desc {
            font-size: 15px;
            color: var(--text-secondary);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .pm-info {
            background: var(--off-white);
            border: 1px solid var(--border-light);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 20px;
            text-align: left;
        }

        .pm-info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            margin-bottom: 7px;
            color: var(--text-secondary);
        }

        .pm-info-row:last-child {
            margin-bottom: 0;
        }

        .pm-info-icon {
            width: 20px;
            text-align: center;
            color: var(--primary-blue);
        }

        .pm-warn {
            background: rgba(244, 162, 97, .1);
            border: 1px solid rgba(244, 162, 97, .25);
            border-radius: 10px;
            padding: 11px 14px;
            color: var(--amber);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
        }

        .pm-btns {
            display: flex;
            gap: 12px;
        }

        .btn-cancel {
            flex: 1;
            padding: 13px;
            border-radius: 50px;
            background: transparent;
            border: 2px solid var(--border-light);
            color: var(--text-primary);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-cancel:hover {
            border-color: var(--primary-blue);
            background: var(--light-blue);
            color: var(--primary-blue);
        }

        .btn-send {
            flex: 1;
            padding: 13px;
            border-radius: 50px;
            background: var(--red);
            border: none;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: .2s;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-send:hover {
            background: #c0000a;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(230, 57, 70, .45);
        }

        .btn-send:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }

        /* ── LOGOUT MODAL ── */
        #logoutModal .modal {
            max-width: 380px;
            width: 100%;
            text-align: center;
        }

        .lm-icon {
            font-size: 56px;
            margin-bottom: 14px;
            color: var(--primary-blue);
        }

        .lm-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .lm-desc {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 22px;
            line-height: 1.6;
        }

        .lm-btns {
            display: flex;
            gap: 12px;
        }

        .btn-logout {
            flex: 1;
            padding: 12px;
            border-radius: 50px;
            background: #dc3545;
            border: none;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-logout:hover {
            background: #b02a37;
        }

        /* ── TOAST ── */
        .toast {
            position: fixed;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            padding: 14px 24px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: var(--shadow-md);
            z-index: 20000;
            opacity: 0;
            transition: opacity .3s;
            max-width: 90vw;
            text-align: center;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast.show {
            opacity: 1;
        }

        .toast.success {
            background: rgba(45, 198, 83, .9);
            border: 1px solid rgba(45, 198, 83, .3);
            color: #fff;
        }

        .toast.error {
            background: rgba(230, 57, 70, .9);
            border: 1px solid rgba(230, 57, 70, .3);
            color: #fff;
        }

        .toast.warning {
            background: rgba(244, 162, 97, .9);
            border: 1px solid rgba(244, 162, 97, .3);
            color: #000;
        }

        /* ══════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════ */
        @media(max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .modal-grid {
                grid-template-columns: 1fr;
            }

            .panic-wrap {
                flex-direction: column;
                gap: 24px;
                padding: 16px 8px 24px;
            }

            .panic-info-text {
                align-items: center;
                text-align: center;
                max-width: 100%;
                width: 100%;
                padding: 0 10px;
            }

            .panic-description {
                max-width: 400px;
                margin: 0 auto;
            }

            .card:first-child {
                min-height: auto;
                padding-bottom: 32px;
            }

            #pbMap {
                height: 260px;
                min-height: 260px;
            }
        }

        @media(max-width: 600px) {
            .wrap {
                padding: 12px;
            }

            .header {
                flex-direction: column;
                gap: 12px;
                text-align: center;
                padding: 14px 16px;
            }

            .brand {
                justify-content: center;
            }

            .pm-btns,
            .lm-btns {
                flex-direction: column;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .panic-btn {
                width: 160px;
                height: 160px;
            }

            .pb-icon {
                font-size: 44px;
            }

            .pb-label {
                font-size: 22px;
            }

            .panic-wrap {
                gap: 18px;
                padding: 10px 5px 20px;
            }

            .panic-info-text {
                padding: 0 5px;
            }

            .panic-description {
                font-size: 13px;
                max-width: 300px;
            }

            .card:first-child {
                padding-bottom: 28px;
            }

            .card {
                min-height: 0;
            }

            #pbMap {
                height: 220px;
                min-height: 220px;
            }

            /* Profile modal */
            .modal {
                padding: 20px 14px;
                border-radius: 16px;
            }

            .modal-h h2 {
                font-size: 20px;
            }

            .modal-grid {
                gap: 14px;
            }

            .modal-section-divider {
                margin: 14px 0;
            }

            .data-section {
                padding: 14px;
            }

            .sec-title {
                font-size: 14px;
            }

            /* Data diri: label atas, value bawah */
            .dr {
                flex-direction: column;
                gap: 1px;
                padding: 10px 0;
            }

            .dl {
                width: 100%;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: .4px;
                color: #94a3b8;
            }

            .dv {
                font-size: 14px;
                padding-left: 2px;
            }

            /* Dropdown pill */
            .profile-name {
                font-size: 14px;
            }

            .profile-guid {
                font-size: 10px;
            }
        }

        @media(max-width: 400px) {
            .brand-name {
                font-size: 16px;
                letter-spacing: .5px;
            }

            .panic-btn {
                width: 140px;
                height: 140px;
            }

            .pb-icon {
                font-size: 38px;
            }

            .pb-label {
                font-size: 20px;
            }

            .pb-sub {
                font-size: 11px;
            }
        }
    </style>
    @vite(['resources/js/app.js'])
</head>

<body>
    <div class="wrap">

        {{-- HEADER --}}
        <div class="header">
            <div class="brand">
                <img src="{{ asset('asset/logo.png') }}" alt="logo" />
                <div class="brand-name">PANIC BUTTON — <span>GINTARA NET</span></div>
            </div>
            <div class="profile-area" id="profileArea">
                <div class="profile-btn" onclick="toggleDropdown()">
                    <div class="avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                    <div>
                        <div class="profile-name">{{ $user->name }}</div>
                        <div class="profile-guid">{{ $panicButton?->GUID ?? 'Panic button belum aktif' }}</div>
                    </div>
                    <span style="color:var(--text-muted);font-size:11px;margin-left:4px;"><i
                            class="bi bi-chevron-down"></i></span>
                </div>
                <div class="dropdown" id="dropdown">
                    <div class="dd-head">
                        <div class="dname">{{ $user->name }}</div>
                        <div class="demail">{{ $user->email }}</div>
                    </div>
                    <div class="dd-item" onclick="openProfile()">
                        <span class="di-icon"><i class="bi bi-person-circle"></i></span>
                        <div>
                            <div class="di-title">Profil Saya</div>
                            <div class="di-sub">Lihat & edit data diri</div>
                        </div>
                    </div>
                    <div class="dd-item" onclick="openLogout()">
                        <span class="di-icon"><i class="bi bi-box-arrow-right"></i></span>
                        <div>
                            <div class="di-title">Logout</div>
                            <div class="di-sub">Keluar dari akun</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SESSION ALERT — otomatis hilang setelah 10 detik --}}
        @if (session('success'))
            <div class="session-alert success" id="sessionAlert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="session-alert error" id="sessionAlert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif

        {{-- MAIN GRID --}}
        <div class="grid">
            {{-- KOLOM KIRI --}}
            <div class="col">
                {{-- Panic Button Card --}}
                <div class="card">
                    <div class="card-head">
                        <span class="ch-icon"><i class="bi bi-shield-fill-exclamation"></i></span> Panic Button
                    </div>
                    <div class="card-body">
                        <div class="panic-wrap">
                            @php $isDarurat = $panicButton?->state === 'Darurat'; @endphp
                            <div class="panic-ring {{ $isDarurat ? 'active' : '' }}">
                                <button class="panic-btn {{ $isDarurat ? 'darurat' : '' }}" id="panicBtn"
                                    onclick="openPanic()" {{ $isDarurat || !$panicButton ? 'disabled' : '' }}>
                                    <div class="pb-icon">
                                        <i
                                            class="bi {{ $isDarurat ? 'bi-bell-fill' : 'bi-exclamation-triangle-fill' }}"></i>
                                    </div>
                                    <div class="pb-label">{{ $isDarurat ? 'DARURAT' : 'PANIC' }}</div>
                                    <div class="pb-sub">{{ $isDarurat ? 'BANTUAN DIPANGGIL' : 'TEKAN DARURAT' }}</div>
                                </button>
                            </div>
                            <div class="panic-info-text">
                                <div class="panic-title">
                                    <i class="bi bi-shield-fill-check"></i>
                                    {{ $isDarurat ? 'Status Darurat Aktif' : 'Sistem Siaga 24/7' }}
                                </div>
                                <div class="panic-description">
                                    @if ($isDarurat)
                                        Tim teknisi sedang menuju lokasi Anda. Tetap tenang dan jangan panik. Kami akan
                                        segera membantu.
                                    @else
                                        Tekan tombol panic jika mengalami gangguan WiFi atau keadaan darurat. Tim kami
                                        akan segera merespon.
                                    @endif
                                </div>
                                @if ($isDarurat)
                                    <div class="panic-status status-darurat" id="panicStatus">
                                        <i class="bi bi-exclamation-circle-fill"></i> DARURAT — Menunggu Tim
                                    </div>
                                @elseif (!$panicButton)
                                    <div class="panic-status" id="panicStatus"
                                        style="background:var(--off-white);border-color:var(--border-light);color:var(--text-muted);">
                                        <i class="bi bi-exclamation-triangle"></i> Belum aktif
                                    </div>
                                @else
                                    <div id="panicStatus" style="display:none;"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Card --}}
                <div class="card">
                    <div class="card-head">
                        <span class="ch-icon"><i class="bi bi-clock-history"></i></span> Riwayat Aktivitas
                        @if ($riwayat->count() > 0)
                            <span id="timelineCounter"
                                style="margin-left:auto;font-size:13px;background:var(--light-blue);padding:4px 12px;border-radius:50px;color:var(--primary-blue);">
                                {{ $riwayat->count() }} total
                            </span>
                        @else
                            <span id="timelineCounter"
                                style="display:none;margin-left:auto;font-size:13px;background:var(--light-blue);padding:4px 12px;border-radius:50px;color:var(--primary-blue);"></span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="timeline" id="timeline">
                            @forelse ($riwayat as $index => $r)
                                @php
                                    $icon = match ($r->status) {
                                        'Menunggu' => '<i class="bi bi-bell-fill"></i>',
                                        'Diproses' => '<i class="bi bi-person-walking"></i>',
                                        'Selesai' => '<i class="bi bi-shield-check"></i>',
                                        default => '<i class="bi bi-clock-history"></i>',
                                    };
                                    $cls = match ($r->status) {
                                        'Menunggu' => 'tl-alarm',
                                        'Diproses' => 'tl-proses',
                                        default => 'tl-selesai',
                                    };
                                    $bdgCls = match ($r->status) {
                                        'Menunggu' => 'badge-menunggu',
                                        'Diproses' => 'badge-diproses',
                                        default => 'badge-selesai',
                                    };
                                    $label = match ($r->status) {
                                        'Menunggu' => 'Menunggu Tim',
                                        'Diproses' => 'Tim Menuju Lokasi',
                                        'Selesai' => 'Bantuan Selesai',
                                        default => $r->status,
                                    };
                                    $title = match ($r->status) {
                                        'Menunggu' => 'Panic Button Ditekan',
                                        'Diproses' => 'Admin Sedang Menuju Lokasi',
                                        'Selesai' => 'Bantuan Telah Selesai',
                                        default => 'Aktivitas Panic Button',
                                    };
                                    $waktuTrigger = $r->waktu_trigger ? $r->waktu_trigger->format('d M Y, H:i') : '-';
                                    $waktuSelesai = $r->waktu_selesai
                                        ? '→ Selesai ' . $r->waktu_selesai->format('H:i') . ' WIB'
                                        : '';
                                    $durasi = '';
                                    if ($r->waktu_trigger && $r->waktu_selesai) {
                                        $diff = $r->waktu_trigger->diff($r->waktu_selesai);
                                        $durasi = ($diff->h > 0 ? $diff->h . ' jam ' : '') . $diff->i . ' menit';
                                    }
                                @endphp
                                <div class="tl-item" data-alarm-id="{{ $r->id }}"
                                    style="animation-delay:{{ $index * 0.05 }}s">
                                    <div class="tl-icon-wrap {{ $cls }}">{!! $icon !!}</div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="tl-title">{{ $title }}</div>
                                        <div class="tl-time">
                                            {{ $waktuTrigger }} WIB
                                            @if ($waktuSelesai)
                                                <br><span style="color:var(--green);">{{ $waktuSelesai }}</span>
                                            @endif
                                        </div>
                                        @if ($durasi)
                                            <div class="tl-extra"><i class="bi bi-hourglass-split"></i> Durasi:
                                                {{ $durasi }}</div>
                                        @endif
                                        <div class="tl-badges-row"
                                            style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:6px;">
                                            <span class="tl-badge {{ $bdgCls }}">{!! $icon !!}
                                                {{ $label }}</span>
                                            @if ($r->lokasi)
                                                <span class="tl-badge"
                                                    style="background:rgba(74,163,255,.1);color:var(--primary-blue);border-color:rgba(74,163,255,.2);">
                                                    <i class="bi bi-geo-alt"></i> {{ $r->lokasi->latitude ?? '-' }},
                                                    {{ $r->lokasi->longtitude ?? '-' }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($r->admin)
                                            <div class="tl-extra" data-type="admin" style="margin-top:4px;">
                                                <i class="bi bi-person-badge"></i> Ditangani oleh:
                                                {{ $r->admin->name ?? 'Admin' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="tl-empty">
                                    <div class="icon"><i class="bi bi-inbox"></i></div>
                                    <div>Belum ada riwayat aktivitas</div>
                                    <div style="font-size:12px;margin-top:8px;color:var(--text-muted);">Riwayat akan
                                        muncul saat Anda menggunakan panic button</div>
                                </div>
                            @endforelse
                        </div>
                        @if ($riwayat->count() > 0)
                            <div
                                style="margin-top:16px;padding-top:12px;border-top:1px solid var(--border-light);text-align:center;">
                                <span style="font-size:12px;color:var(--text-muted);"><i
                                        class="bi bi-arrow-up-short"></i> Menampilkan {{ $riwayat->count() }} riwayat
                                    terbaru</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="col">
                {{-- Map Card --}}
                <div class="card">
                    <div class="card-head">
                        <span class="ch-icon"><i class="bi bi-map"></i></span> Lokasi Panic Button
                    </div>
                    <div class="card-body">
                        <div id="pbMap"></div>
                    </div>
                </div>

                {{-- Info Keamanan Card --}}
                <div class="card">
                    <div class="card-head">
                        <span class="ch-icon"><i class="bi bi-shield-lock"></i></span> Informasi Keamanan
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-cell">
                                <div class="ic-label"><i class="bi bi-person"></i> Nama</div>
                                <div class="ic-value">{{ $user->name }}</div>
                            </div>
                            <div class="info-cell">
                                <div class="ic-label"><i class="bi bi-geo"></i> Wilayah</div>
                                <div class="ic-value">{{ $panicButton?->wilayah?->nama ?? '—' }}</div>
                            </div>
                            <div class="info-cell">
                                <div class="ic-label"><i class="bi bi-house"></i> Alamat</div>
                                <div class="ic-value" style="font-size:13px;">Blok
                                    {{ $panicButton?->GetBlockID ?? '—' }} / {{ $panicButton?->GetNumber ?? '—' }}
                                </div>
                            </div>
                            <div class="info-cell">
                                <div class="ic-label"><i class="bi bi-bell"></i> Notifikasi Terakhir</div>
                                <div class="ic-value" style="font-size:13px;">
                                    {{ $riwayat->first()?->waktu_trigger?->format('d M Y') ?? 'Belum ada' }}</div>
                            </div>
                            <div class="info-cell" style="grid-column:span 2;">
                                <div class="ic-label"><i class="bi bi-shield"></i> Status Panic Button</div>
                                <div class="ic-value" id="statusDotCell">
                                    @if ($isDarurat)
                                        <span class="dot-darurat"></span>Darurat
                                    @else
                                        <span class="dot-aman"></span>Aman
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════
         PROFILE MODAL
         Baris 1: Data Diri (kiri) | Ganti Email (kanan)
         Baris 2: Ganti No. HP (kiri) | Ganti Password (kanan)
    ════════════════════════════════════════════════════════ --}}
        <div class="modal-bg" id="profileModal">
            <div class="modal" style="max-width:960px;width:100%;">
                <span class="modal-close" onclick="closeModal('profileModal')"><i class="bi bi-x-lg"></i></span>
                <div class="modal-h">
                    <h2><i class="bi bi-person-circle"></i> Profil Saya</h2>
                    <p>Informasi data diri dan pengaturan akun</p>
                </div>

                {{-- ── BARIS 1: Data Diri + Ganti Email ── --}}
                <div class="modal-grid" style="margin-bottom:18px;">

                    {{-- Data Diri --}}
                    <div class="data-section">
                        <div class="sec-title"><i class="bi bi-card-list"></i> Data Diri</div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-person"></i> Nama</div>
                            <div class="dv">{{ $user->name }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-envelope"></i> Email</div>
                            <div class="dv">{{ $user->email }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-phone"></i> No. HP</div>
                            <div class="dv">{{ $user->no_hp }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i
                                    class="bi bi-gender-{{ strtolower($user->jenis_kelamin) == 'laki-laki' ? 'male' : 'female' }}"></i>
                                Jenis Kelamin</div>
                            <div class="dv">{{ $user->jenis_kelamin }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-credit-card"></i> NIK</div>
                            <div class="dv" style="font-family:monospace;">{{ $pelanggan->nik }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-calendar"></i>TTL</div>
                            <div class="dv">{{ $pelanggan->ttl }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-geo-alt"></i> Alamat</div>
                            <div class="dv">{{ $pelanggan->alamat }}, RT {{ $pelanggan->RT }}/RW
                                {{ $pelanggan->RW }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-tree"></i> Desa</div>
                            <div class="dv">{{ $pelanggan->desa }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-building"></i> Kecamatan</div>
                            <div class="dv">{{ $pelanggan->kecamatan }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-shield"></i> Wilayah</div>
                            <div class="dv">{{ $panicButton?->wilayah?->nama ?? '—' }}</div>
                        </div>
                        <div class="dr">
                            <div class="dl"><i class="bi bi-check-circle"></i> Status</div>
                            <div class="dv">
                                <span
                                    style="background:#f0fff4;color:var(--green);padding:3px 12px;border-radius:50px;font-size:12px;font-weight:600;border:1px solid #c6f6d5;display:flex;align-items:center;gap:4px;width:fit-content;">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Ganti Email — kanan baris 1 --}}
                    <form method="POST" action="{{ route('dashboard.update-profile') }}">
                        @csrf
                        <input type="hidden" name="_action" value="email" />
                        <div class="data-section" style="height:100%;display:flex;flex-direction:column;">
                            <div class="sec-title"><i class="bi bi-envelope-at"></i> Ganti Email</div>
                            <p class="form-note">
                                <i class="bi bi-info-circle"></i>
                                Notifikasi perubahan akan dikirim ke <strong>WhatsApp</strong> dan <strong>email
                                    lama</strong> Anda sebagai konfirmasi keamanan.
                            </p>
                            <div class="fgroup">
                                <label><i class="bi bi-envelope"></i> Email Saat Ini</label>
                                <div
                                    style="padding:10px 13px;background:var(--off-white);border:1px solid var(--border-light);border-radius:9px;font-size:14px;color:var(--primary-blue);font-family:monospace;display:flex;align-items:center;gap:4px;word-break:break-all;">
                                    <i class="bi bi-check-circle-fill" style="flex-shrink:0;"></i>
                                    {{ $user->email }}
                                </div>
                            </div>
                            <div class="fgroup">
                                <label><i class="bi bi-envelope"></i> Email Baru <span style="color:var(--red)">*</span></label>
                                <input type="email" name="new_email" class="finput" placeholder="Masukkan email baru"
                                    value="{{ old('new_email') }}" required />
                            </div>
                            <div class="fgroup">
                                <label><i class="bi bi-key"></i> Password Konfirmasi <span style="color:var(--red)">*</span></label>
                                <div class="pass-wrap">
                                    <input type="password" name="current_password" class="finput"
                                        placeholder="Password saat ini" required />
                                    <button type="button" class="pass-toggle" onclick="togglePass(this)"
                                        tabindex="-1"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="form-warning">
                                <p><i class="bi bi-exclamation-triangle-fill"></i> <strong>Perhatian:</strong> Setelah
                                    email diubah, gunakan email baru untuk login ke sistem.</p>
                            </div>
                            <div style="margin-top:auto;">
                                <button type="submit" class="fbtn"><i class="bi bi-envelope-check"></i> Ubah
                                    Email</button>
                            </div>
                        </div>
                    </form>
                </div>

                <hr class="modal-section-divider" />

                {{-- ── BARIS 2: Ganti No. HP + Ganti Password ── --}}
                <div class="modal-grid">

                    {{-- Ganti No. HP — kiri baris 2 --}}
                    <form method="POST" action="{{ route('dashboard.update-profile') }}">
                        @csrf
                        <input type="hidden" name="_action" value="nomor_hp" />
                        <div class="data-section">
                            <div class="sec-title"><i class="bi bi-phone"></i> Ganti No. HP</div>
                            <p class="form-note">
                                <i class="bi bi-info-circle"></i>
                                Notifikasi perubahan akan dikirim ke <strong>WhatsApp nomor baru</strong> dan
                                <strong>email</strong> Anda.
                            </p>
                            <div class="fgroup">
                                <label><i class="bi bi-phone"></i> No. HP Saat Ini</label>
                                <div
                                    style="padding:10px 13px;background:var(--off-white);border:1px solid var(--border-light);border-radius:9px;font-size:14px;color:var(--primary-blue);font-family:monospace;display:flex;align-items:center;gap:4px;">
                                    <i class="bi bi-check-circle-fill"></i> {{ $user->no_hp }}
                                </div>
                            </div>
                            <div class="fgroup">
                                <label><i class="bi bi-phone"></i> No. HP Baru <span style="color:var(--red)">*</span></label>
                                <input type="tel" name="no_hp" class="finput" placeholder="08xxxxxxxxxx"
                                    value="{{ old('no_hp') }}" required />
                            </div>
                            <div class="fgroup">
                                <label><i class="bi bi-key"></i> Password Konfirmasi <span style="color:var(--red)">*</span></label>
                                <div class="pass-wrap">
                                    <input type="password" name="current_password" class="finput"
                                        placeholder="Password saat ini" required />
                                    <button type="button" class="pass-toggle" onclick="togglePass(this)"
                                        tabindex="-1"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <button type="submit" class="fbtn"><i class="bi bi-save"></i> Simpan No. HP</button>
                        </div>
                    </form>

                    {{-- Ganti Password --}}
                    <form method="POST" action="{{ route('dashboard.update-profile') }}">
                        @csrf
                        <input type="hidden" name="_action" value="password" />
                        <div class="data-section">
                            <div class="sec-title"><i class="bi bi-key"></i> Ganti Password</div>
                            <p class="form-note">
                                <i class="bi bi-info-circle"></i>
                                Notifikasi perubahan akan dikirim ke <strong>WhatsApp</strong> dan
                                <strong>email</strong> Anda.
                            </p>
                            <div class="fgroup">
                                <label><i class="bi bi-key"></i> Password Saat Ini <span style="color:var(--red)">*</span></label>
                                <div class="pass-wrap">
                                    <input type="password" name="current_password" class="finput"
                                        placeholder="Password lama" required />
                                    <button type="button" class="pass-toggle" onclick="togglePass(this)"
                                        tabindex="-1"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="fgroup">
                                <label><i class="bi bi-key"></i> Password Baru <span style="color:var(--red)">*</span></label>
                                <div class="pass-wrap">
                                    <input type="password" name="new_password" class="finput"
                                        placeholder="Min. 6 karakter" minlength="6" required />
                                    <button type="button" class="pass-toggle" onclick="togglePass(this)"
                                        tabindex="-1"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="fgroup">
                                <label><i class="bi bi-key"></i> Konfirmasi Password Baru <span style="color:var(--red)">*</span></label>
                                <div class="pass-wrap">
                                    <input type="password" name="new_password_confirmation" class="finput"
                                        placeholder="Ulangi password baru" required />
                                    <button type="button" class="pass-toggle" onclick="togglePass(this)"
                                        tabindex="-1"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <button type="submit" class="fbtn"><i class="bi bi-key"></i> Ubah Password</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer-close">
                    <button class="btn-footer-close" onclick="closeModal('profileModal')">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- PANIC MODAL --}}
        <div class="modal-bg" id="panicModal">
            <div class="modal">
                <span class="modal-close" onclick="closeModal('panicModal')"><i class="bi bi-x-lg"></i></span>
                <div class="pm-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div class="pm-title">Konfirmasi Darurat</div>
                <div class="pm-desc">Anda akan mengirim sinyal darurat. Tim admin wilayah akan segera diberitahu.</div>
                <div class="pm-info">
                    <div class="pm-info-row">
                        <span class="pm-info-icon"><i class="bi bi-geo-alt-fill"></i></span>
                        <span>Lokasi:
                            @if ($panicButton?->lokasi)
                                {{ $panicButton->lokasi->latitude }}, {{ $panicButton->lokasi->longtitude }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="pm-info-row">
                        <span class="pm-info-icon"><i class="bi bi-building"></i></span>
                        <span>Wilayah: {{ $panicButton?->wilayah?->nama ?? '—' }}</span>
                    </div>
                    <div class="pm-info-row">
                        <span class="pm-info-icon"><i class="bi bi-clock"></i></span>
                        <span id="panicTime">—</span>
                    </div>
                </div>
                <div class="pm-warn"><i class="bi bi-exclamation-triangle-fill"></i> Gunakan hanya dalam keadaan
                    darurat nyata.</div>
                <div class="pm-btns">
                    <button class="btn-cancel" onclick="closeModal('panicModal')"><i class="bi bi-x-lg"></i>
                        Batal</button>
                    <button class="btn-send" id="btnSend" onclick="sendPanic()"><i class="bi bi-send-fill"></i>
                        KIRIM DARURAT</button>
                </div>
            </div>
        </div>

        {{-- LOGOUT MODAL --}}
        <div class="modal-bg" id="logoutModal">
            <div class="modal">
                <span class="modal-close" onclick="closeModal('logoutModal')"><i class="bi bi-x-lg"></i></span>
                <div class="lm-icon"><i class="bi bi-box-arrow-right"></i></div>
                <div class="lm-title">Konfirmasi Logout</div>
                <div class="lm-desc">Yakin ingin keluar dari sesi ini?</div>
                <div class="lm-btns">
                    <button class="btn-cancel" onclick="closeModal('logoutModal')"><i class="bi bi-x-lg"></i>
                        Batal</button>
                    <form method="POST" action="{{ route('logout') }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn-logout" style="width:100%;"><i class="bi bi-check-lg"></i>
                            Ya, Logout</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- TOAST --}}
        <div class="toast" id="toast"></div>

    </div>{{-- /wrap --}}

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('sessionAlert');
            if (!alert) return;
            setTimeout(() => {
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 700);
            }, 10000);
        });

        function togglePass(btn) {
            const input = btn.closest('.pass-wrap').querySelector('input');
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        window._pbMarker = null;

        function initMap() {
            @if ($panicButton?->lokasi)
                const lat = {{ $panicButton->lokasi->latitude }};
                const lng = {{ $panicButton->lokasi->longtitude }};
                const mapElement = document.getElementById('pbMap');
                if (!mapElement) return;
                mapElement.innerHTML = '';
                const map = L.map('pbMap', {
                    zoomControl: true,
                    scrollWheelZoom: false,
                    fadeAnimation: true,
                    zoomAnimation: true
                }).setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap',
                    detectRetina: true
                }).addTo(map);
                const markerColor = '{{ $panicButton->state === 'Darurat' ? '#f4a261' : '#2dc653' }}';
                const icon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div id="pbMarkerDot" style="width:18px;height:18px;background:${markerColor};border:3px solid #fff;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.4);transition:background .5s ease;"></div>`,
                    iconSize: [18, 18],
                    iconAnchor: [9, 9]
                });
                window._pbMarker = L.marker([lat, lng], {
                        icon
                    })
                    .addTo(map)
                    .bindPopup(
                        '<i class="bi bi-house-fill"></i> Lokasi rumah: <strong>{{ addslashes($user->name) }}</strong>')
                    .openPopup();
                setTimeout(() => map.invalidateSize(), 100);
                window.addEventListener('resize', () => setTimeout(() => map.invalidateSize(), 100));
            @else
                document.getElementById('pbMap').innerHTML =
                    '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--text-muted);font-size:13px;"><i class="bi bi-geo-alt"></i>&nbsp;Koordinat belum tersedia</div>';
            @endif
        }
        document.addEventListener('DOMContentLoaded', initMap);

        function toggleDropdown() {
            document.getElementById('dropdown').classList.toggle('open');
        }
        document.addEventListener('click', e => {
            if (!e.target.closest('#profileArea')) document.getElementById('dropdown').classList.remove('open');
        });

        function openProfile() {
            document.getElementById('dropdown').classList.remove('open');
            document.getElementById('profileModal').classList.add('open');
        }

        function openLogout() {
            document.getElementById('dropdown').classList.remove('open');
            document.getElementById('logoutModal').classList.add('open');
        }

        function openPanic() {
            const now = new Date();
            document.getElementById('panicTime').innerHTML = '<i class="bi bi-clock"></i> ' + now.toLocaleString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) + ' WIB';
            document.getElementById('panicModal').classList.add('open');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        document.querySelectorAll('.modal-bg').forEach(bg => {
            bg.addEventListener('click', e => {
                if (e.target === bg) bg.classList.remove('open');
            });
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') document.querySelectorAll('.modal-bg').forEach(m => m.classList.remove('open'));
        });

        function showToast(msg, type = 'success', duration = 4000) {
            const t = document.getElementById('toast');
            const icon = type === 'success' ? 'bi-check-circle-fill' : type === 'error' ? 'bi-exclamation-triangle-fill' :
                'bi-exclamation-circle-fill';
            t.innerHTML = `<i class="bi ${icon}"></i> ${msg}`;
            t.className = `toast ${type} show`;
            setTimeout(() => t.classList.remove('show'), duration);
        }
    </script>

    <script>
        (function() {
            function setStatusDot(isDarurat) {
                const cell = document.getElementById('statusDotCell');
                if (!cell) return;
                cell.innerHTML = isDarurat ? '<span class="dot-darurat"></span>Darurat' :
                    '<span class="dot-aman"></span>Aman';
            }

            function setMapMarkerColor(color) {
                if (!window._pbMarker || !window._pbMarker._icon) return;
                const dot = window._pbMarker._icon.querySelector('div');
                if (dot) dot.style.background = color;
            }

            function setPanicUIDarurat() {
                const btn = document.getElementById('panicBtn');
                if (btn) {
                    btn.classList.add('darurat');
                    btn.disabled = true;
                    btn.querySelector('.pb-icon').innerHTML = '<i class="bi bi-bell-fill"></i>';
                    btn.querySelector('.pb-label').textContent = 'DARURAT';
                    btn.querySelector('.pb-sub').textContent = 'BANTUAN DIPANGGIL';
                }
                document.querySelector('.panic-ring')?.classList.add('active');
                const status = document.getElementById('panicStatus');
                if (status) {
                    status.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> DARURAT — Menunggu Tim';
                    status.className = 'panic-status status-darurat';
                    status.style.display = '';
                }
                const panicTitle = document.querySelector('.panic-title');
                if (panicTitle) panicTitle.innerHTML = '<i class="bi bi-shield-fill-check"></i> Status Darurat Aktif';
                const panicDesc = document.querySelector('.panic-description');
                if (panicDesc) panicDesc.textContent =
                    'Tim teknisi sedang menuju lokasi Anda. Tetap tenang dan jangan panik.';
            }

            function setPanicUIAman() {
                const btn = document.getElementById('panicBtn');
                if (btn) {
                    btn.classList.remove('darurat');
                    btn.disabled = false;
                    btn.querySelector('.pb-icon').innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>';
                    btn.querySelector('.pb-label').textContent = 'PANIC';
                    btn.querySelector('.pb-sub').textContent = 'TEKAN DARURAT';
                }
                document.querySelector('.panic-ring')?.classList.remove('active');
                const status = document.getElementById('panicStatus');
                if (status) status.style.display = 'none';
                const panicTitle = document.querySelector('.panic-title');
                if (panicTitle) panicTitle.innerHTML = '<i class="bi bi-shield-fill-check"></i> Sistem Siaga 24/7';
                const panicDesc = document.querySelector('.panic-description');
                if (panicDesc) panicDesc.textContent =
                    'Tekan tombol panic jika mengalami gangguan WiFi atau keadaan darurat. Tim kami akan segera merespon.';
            }

            function upsertTimeline(alarmId, state, adminNama) {
                const tl = document.getElementById('timeline');
                if (!tl) return;
                tl.querySelector('.tl-empty')?.remove();
                const cfg = {
                    Menunggu: {
                        iconCls: 'tl-alarm',
                        icon: 'bi-bell-fill',
                        title: 'Panic Button Ditekan',
                        badgeCls: 'badge-menunggu',
                        label: 'Menunggu Tim'
                    },
                    Diproses: {
                        iconCls: 'tl-proses',
                        icon: 'bi-person-walking',
                        title: 'Admin Sedang Menuju Lokasi',
                        badgeCls: 'badge-diproses',
                        label: 'Tim Menuju Lokasi'
                    },
                    Selesai: {
                        iconCls: 'tl-selesai',
                        icon: 'bi-shield-check',
                        title: 'Bantuan Telah Selesai',
                        badgeCls: 'badge-selesai',
                        label: 'Bantuan Selesai'
                    },
                } [state] ?? {
                    iconCls: 'tl-selesai',
                    icon: 'bi-clock-history',
                    title: state,
                    badgeCls: 'badge-selesai',
                    label: state
                };
                const adminHtml = adminNama ?
                    `<div class="tl-extra" style="margin-top:4px;"><i class="bi bi-person-badge"></i> Ditangani oleh: ${adminNama}</div>` :
                    '';
                let item = tl.querySelector(`[data-alarm-id="${alarmId}"]`);
                if (item) {
                    const iconWrap = item.querySelector('.tl-icon-wrap');
                    if (iconWrap) {
                        iconWrap.className = `tl-icon-wrap ${cfg.iconCls}`;
                        iconWrap.innerHTML = `<i class="bi ${cfg.icon}"></i>`;
                    }
                    const titleEl = item.querySelector('.tl-title');
                    if (titleEl) titleEl.textContent = cfg.title;
                    const badgesRow = item.querySelector('.tl-badges-row');
                    if (badgesRow) {
                        const fb = badgesRow.querySelector('.tl-badge');
                        if (fb) {
                            fb.className = `tl-badge ${cfg.badgeCls}`;
                            fb.innerHTML = `<i class="bi ${cfg.icon}"></i> ${cfg.label}`;
                        }
                    }
                    let adminEl = item.querySelector('.tl-extra[data-type="admin"]');
                    if (adminNama) {
                        if (!adminEl) {
                            adminEl = document.createElement('div');
                            adminEl.className = 'tl-extra';
                            adminEl.dataset.type = 'admin';
                            adminEl.style.marginTop = '4px';
                            if (badgesRow) badgesRow.before(adminEl);
                        }
                        adminEl.innerHTML = `<i class="bi bi-person-badge"></i> Ditangani oleh: ${adminNama}`;
                    }
                    if (state === 'Selesai') {
                        const timeEl = item.querySelector('.tl-time');
                        if (timeEl && !timeEl.querySelector('.waktu-selesai')) {
                            const selesaiStr = new Date().toLocaleString('id-ID', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            const selesaiEl = document.createElement('span');
                            selesaiEl.className = 'waktu-selesai';
                            selesaiEl.style.color = 'var(--green)';
                            selesaiEl.style.display = 'block';
                            selesaiEl.innerHTML = `→ Selesai ${selesaiStr} WIB`;
                            timeEl.appendChild(selesaiEl);
                        }
                    }
                    item.style.transition = 'background .3s';
                    item.style.background = state === 'Selesai' ? 'rgba(45,198,83,.08)' : 'rgba(74,163,255,.08)';
                    setTimeout(() => item.style.background = '', 1500);
                } else {
                    const timeStr = new Date().toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' WIB';
                    item = document.createElement('div');
                    item.className = 'tl-item';
                    item.dataset.alarmId = alarmId;
                    item.innerHTML = `
                    <div class="tl-icon-wrap ${cfg.iconCls}"><i class="bi ${cfg.icon}"></i></div>
                    <div style="flex:1;min-width:0;">
                        <div class="tl-title">${cfg.title}</div>
                        <div class="tl-time">${timeStr}</div>
                        ${adminHtml}
                        <div class="tl-badges-row" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:6px;">
                            <span class="tl-badge ${cfg.badgeCls}"><i class="bi ${cfg.icon}"></i> ${cfg.label}</span>
                        </div>
                    </div>`;
                    tl.insertBefore(item, tl.firstChild);
                    const counter = document.getElementById('timelineCounter');
                    if (counter) {
                        counter.textContent = `${tl.querySelectorAll('.tl-item').length} total`;
                        counter.style.display = '';
                    }
                }
            }

            window.sendPanic = function() {
                const btn = document.getElementById('btnSend');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
                if (navigator.vibrate) navigator.vibrate([500, 200, 500, 200, 500]);
                fetch('{{ route('dashboard.panic') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                    })
                    .then(r => r.json())
                    .then(data => {
                        closeModal('panicModal');
                        if (data.success) {
                            showToast('<i class="bi bi-check-circle-fill"></i> ' + data.message, 'success',
                                5000);
                            setPanicUIDarurat();
                            setStatusDot(true);
                            setMapMarkerColor('#f4a261');
                            upsertTimeline(data.alarm_id, 'Menunggu', null);
                        } else {
                            showToast('<i class="bi bi-exclamation-triangle-fill"></i> ' + data.message,
                                'warning', 5000);
                        }
                    })
                    .catch(() => {
                        showToast(
                            '<i class="bi bi-exclamation-triangle-fill"></i> Gagal mengirim sinyal. Periksa koneksi Anda.',
                            'error');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-send-fill"></i> KIRIM DARURAT';
                    });
            };

            document.addEventListener('DOMContentLoaded', function() {
                if (typeof window.Echo === 'undefined') {
                    console.warn('[RT] Laravel Echo tidak tersedia.');
                    return;
                }
                @if (!Auth::user()->pelanggan)
                    return;
                @endif
                const pelangganId = {{ Auth::user()->pelanggan->id }};
                window.Echo.private(`pelanggan.${pelangganId}`)
                    .listen('.alarm.diproses', (e) => {
                        setPanicUIDarurat();
                        setStatusDot(true);
                        setMapMarkerColor('#f4a261');
                        upsertTimeline(e.alarm_id, 'Diproses', e.admin_nama ?? null);
                        showToast('👤 Tim sedang dalam perjalanan menuju lokasi Anda.', 'warning', 6000);
                    })
                    .listen('.alarm.selesai', (e) => {
                        setPanicUIAman();
                        setStatusDot(false);
                        setMapMarkerColor('#2dc653');
                        upsertTimeline(e.alarm_id, 'Selesai', e.admin_nama ?? null);
                        showToast('✅ Penanganan selesai. Panic button kembali aktif.', 'success', 6000);
                    });
                console.log('[RT] Reverb listener aktif — pelanggan', pelangganId);
            });
        })();
    </script>

</body>

</html>
