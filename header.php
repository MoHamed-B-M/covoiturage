<?php
// Safe session start to prevent conflicts
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpool Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        /* ===== M3 COLOR SYSTEM ===== */
        :root {
            /* Light Mode Colors */
            --md3-primary: #2d6df6;
            --md3-on-primary: #ffffff;
            --md3-primary-container: #dce4ff;
            --md3-on-primary-container: #001048;

            --md3-secondary: #625b71;
            --md3-on-secondary: #ffffff;
            --md3-secondary-container: #e8def8;
            --md3-on-secondary-container: #1e192b;

            --md3-tertiary: #7d5260;
            --md3-on-tertiary: #ffffff;
            --md3-tertiary-container: #ffd8e4;
            --md3-on-tertiary-container: #31111d;

            --md3-success: #00a651;
            --md3-on-success: #ffffff;
            --md3-success-container: #c8f5d0;
            --md3-on-success-container: #002815;

            --md3-error: #b3261e;
            --md3-on-error: #ffffff;
            --md3-error-container: #f9dedc;
            --md3-on-error-container: #410e0b;

            --md3-warning: #ff9800;
            --md3-on-warning: #ffffff;
            --md3-warning-container: #ffe0b2;
            --md3-on-warning-container: #4d2c00;

            --md3-outline: #79747e;
            --md3-outline-variant: #c4c7c5;

            --md3-background: #fffbfe;
            --md3-on-background: #1c1b1f;

            --md3-surface: #fffbfe;
            --md3-on-surface: #1c1b1f;
            --md3-surface-variant: #e7e0ec;
            --md3-on-surface-variant: #49454e;

            --md3-surface-dim: #f3eff4;
            --md3-surface-bright: #fffbfe;
            --md3-surface-container-lowest: #ffffff;
            --md3-surface-container-low: #f7f2fa;
            --md3-surface-container: #f3eff4;
            --md3-surface-container-high: #ede9f0;
            --md3-surface-container-highest: #e8e4eb;

            --sidebar-expanded: 280px;
            --sidebar-collapsed: 88px;

            /* M3 Morphic Easing */
            --m3-transition: cubic-bezier(0.2, 0, 0, 1);
            --m3-morph: cubic-bezier(0.05, 0.7, 0.1, 1);
            --m3-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);

            /* M3 Shape Tokens */
            --m3-radius-xs: 4px;
            --m3-radius-sm: 8px;
            --m3-radius-md: 12px;
            --m3-radius-lg: 16px;
            --m3-radius-xl: 20px;
            --m3-radius-2xl: 28px;
            --m3-radius-full: 32px;
        }

        [data-bs-theme="dark"] {
            /* Dark Mode Colors */
            --md3-primary: #cce4ff;
            --md3-on-primary: #001048;
            --md3-primary-container: #1a4d96;
            --md3-on-primary-container: #cce4ff;

            --md3-secondary: #cbc2db;
            --md3-on-secondary: #332d44;
            --md3-secondary-container: #4a4458;
            --md3-on-secondary-container: #e8def8;

            --md3-tertiary: #ffb3d9;
            --md3-on-tertiary: #492532;
            --md3-tertiary-container: #633b48;
            --md3-on-tertiary-container: #ffd8e4;

            --md3-success: #34d156;
            --md3-on-success: #00210b;
            --md3-success-container: #005820;
            --md3-on-success-container: #c8f5d0;

            --md3-error: #f2b8b5;
            --md3-on-error: #601410;
            --md3-error-container: #8c1d18;
            --md3-on-error-container: #f9dedc;

            --md3-warning: #ffb74d;
            --md3-on-warning: #331000;
            --md3-warning-container: #663300;
            --md3-on-warning-container: #ffe0b2;

            --md3-outline: #9c9a9e;
            --md3-outline-variant: #49454e;

            --md3-background: #1c1b1f;
            --md3-on-background: #e6e1e6;

            --md3-surface: #1c1b1f;
            --md3-on-surface: #e6e1e6;
            --md3-surface-variant: #49454e;
            --md3-on-surface-variant: #c4c7c5;

            --md3-surface-dim: #0f0e13;
            --md3-surface-bright: #302e36;
            --md3-surface-container-lowest: #0f0e13;
            --md3-surface-container-low: #161418;
            --md3-surface-container: #1c1b1f;
            --md3-surface-container-high: #272629;
            --md3-surface-container-highest: #323136;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--md3-background);
            color: var(--md3-on-background);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
            transition: background-color 0.4s var(--m3-morph);
        }

        /* ===== SIDEBAR - M3 EXPRESSIVE ===== */
        .sidebar {
            width: var(--sidebar-expanded);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--md3-surface);
            border-right: 1px solid var(--md3-outline-variant);
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            z-index: 1000;
            transition: width 0.5s var(--m3-bounce), padding 0.5s var(--m3-bounce), background-color 0.4s var(--m3-transition);
            overflow: visible;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
            padding: 24px 12px;
        }

        /* Main Content Margin Adjustment */
        .main-content {
            flex-grow: 1;
            margin-left: var(--sidebar-expanded);
            padding: 32px;
            min-height: 100vh;
            transition: margin-left 0.5s var(--m3-bounce), background-color 0.4s var(--m3-transition);
            background: var(--md3-background);
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* macOS Window Dots */
        .window-dots {
            display: flex;
            gap: 8px;
            margin-bottom: 28px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            transition: transform 0.3s var(--m3-bounce), box-shadow 0.3s ease;
            cursor: pointer;
        }

        .dot:hover {
            transform: scale(1.25) translateY(-2px);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .dot.red { background: linear-gradient(135deg, #ff5f56, #ff8680); }
        .dot.yellow { background: linear-gradient(135deg, #ffbd2e, #ffc640); }
        .dot.green { background: linear-gradient(135deg, #27c93f, #34d156); }

        /* Logo Box - M3 Expressive Shape */
        .logo-box {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--md3-primary) 0%, #5a7fff 100%);
            border-radius: var(--m3-radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--md3-on-primary);
            transition: all 0.4s var(--m3-bounce);
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(45, 109, 246, 0.3);
        }

        .logo-box:hover {
            transform: translateY(-4px) scale(1.08);
            box-shadow: 0 12px 24px rgba(45, 109, 246, 0.4);
        }

        .logo-box i {
            transition: transform 0.4s var(--m3-bounce);
        }

        .logo-box:hover i {
            transform: rotate(20deg) scale(1.1);
        }

        .sidebar.collapsed .logo-box {
            margin-left: 0;
        }

        /* Navigation Styling - M3 Expressive */
        .nav-link {
            color: var(--md3-on-surface-variant);
            padding: 12px 16px;
            border-radius: var(--m3-radius-lg);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.3s var(--m3-morph);
            min-height: 48px;
            position: relative;
            overflow: hidden;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            inset: 0;
            background-color: var(--md3-primary);
            opacity: 0;
            border-radius: var(--m3-radius-lg);
            transition: opacity 0.3s var(--m3-transition);
            z-index: -1;
        }

        .nav-link:hover {
            color: var(--md3-primary);
            transform: translateX(4px);
        }

        .nav-link:hover::after {
            opacity: 0.12;
        }

        .nav-link.active {
            background: var(--md3-primary-container);
            color: var(--md3-primary);
            font-weight: 600;
        }

        .nav-link.active::after {
            opacity: 1;
            background-color: var(--md3-primary);
        }

        .nav-link i {
            font-size: 1.3rem;
            min-width: 40px;
            display: flex;
            justify-content: center;
            transition: transform 0.3s var(--m3-bounce);
        }

        .nav-link:hover i {
            transform: scale(1.15) rotate(5deg);
        }

        .nav-text {
            opacity: 1;
            transition: opacity 0.2s var(--m3-transition);
            margin-left: 8px;
        }

        /* Collapsed Navigation Styles */
        .sidebar.collapsed .nav-text {
            opacity: 0;
            pointer-events: none;
            width: 0;
            margin-left: 0;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px;
        }

        .sidebar.collapsed .nav-link.active {
            width: 48px;
            height: 48px;
            margin-left: auto;
            margin-right: auto;
            border-radius: var(--m3-radius-full);
        }

        /* Floating Toggle Trigger - M3 Expressive */
        .toggle-btn {
            position: absolute;
            right: -18px;
            top: 80px;
            width: 40px;
            height: 40px;
            background: var(--md3-surface);
            border: 2px solid var(--md3-outline-variant);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1100;
            transition: all 0.5s var(--m3-bounce);
            color: var(--md3-on-surface-variant);
        }

        .toggle-btn:hover {
            background: var(--md3-primary);
            color: var(--md3-on-primary);
            border-color: var(--md3-primary);
            box-shadow: 0 8px 20px rgba(45, 109, 246, 0.4);
            transform: scale(1.1);
        }

        .sidebar:not(.collapsed) .toggle-btn {
            transform: rotate(180deg);
        }

        .toggle-btn i {
            transition: transform 0.5s var(--m3-bounce);
        }

        /* ===== M3 EXPRESSIVE CARDS ===== */
        .m3-card,
        .apple-card {
            background: var(--md3-surface);
            border-radius: var(--m3-radius-full);
            padding: 24px;
            border: 1px solid var(--md3-outline-variant);
            transition: all 0.4s var(--m3-morph);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .m3-card::before,
        .apple-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--md3-primary) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.4s var(--m3-transition);
            pointer-events: none;
        }

        .m3-card:hover,
        .apple-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
            border-color: var(--md3-primary);
        }

        .m3-card:hover::before,
        .apple-card:hover::before {
            opacity: 0.08;
        }

        .m3-card > *,
        .apple-card > * {
            position: relative;
            z-index: 1;
        }

        /* ===== TYPOGRAPHY - M3 SYSTEM ===== */
        h1, h2, h3, h4, h5, h6 {
            color: var(--md3-on-background);
            font-weight: 600;
            line-height: 1.2;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        h2 {
            font-size: 2rem;
            font-weight: 600;
            letter-spacing: -0.015em;
        }

        h3 {
            font-size: 1.75rem;
            font-weight: 600;
        }

        h4 {
            font-size: 1.5rem;
            font-weight: 500;
        }

        h5 {
            font-size: 1.25rem;
            font-weight: 500;
        }

        h6 {
            font-size: 1rem;
            font-weight: 500;
        }

        .display-6 {
            font-size: 2.5rem !important;
            font-weight: 700 !important;
        }

        /* ===== BUTTONS - M3 EXPRESSIVE ===== */
        .btn {
            border-radius: var(--m3-radius-full);
            font-weight: 600;
            padding: 10px 24px;
            border: none;
            transition: all 0.3s var(--m3-morph);
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity 0.3s var(--m3-transition);
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn:hover {
            transform: translateY(-2px) scale(1.05);
        }

        .btn:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--md3-primary) 0%, #5a7fff 100%);
            color: var(--md3-on-primary);
            box-shadow: 0 4px 12px rgba(45, 109, 246, 0.3);
        }

        .btn-primary:hover {
            box-shadow: 0 8px 20px rgba(45, 109, 246, 0.4);
            background: linear-gradient(135deg, var(--md3-primary) 0%, #5a7fff 100%);
            color: var(--md3-on-primary);
        }

        .btn-outline-primary {
            color: var(--md3-primary);
            border: 2px solid var(--md3-primary) !important;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--md3-primary-container);
            color: var(--md3-primary);
            border-color: var(--md3-primary) !important;
        }

        .btn-outline-danger {
            color: var(--md3-error);
            border: 2px solid var(--md3-error) !important;
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: var(--md3-error-container);
            color: var(--md3-error);
            border-color: var(--md3-error) !important;
        }

        .btn-apple {
            background: linear-gradient(135deg, var(--md3-primary) 0%, #5a7fff 100%);
            color: var(--md3-on-primary);
            box-shadow: 0 4px 12px rgba(45, 109, 246, 0.3);
            border: none;
        }

        .btn-apple:hover {
            box-shadow: 0 8px 20px rgba(45, 109, 246, 0.4);
            color: var(--md3-on-primary);
            background: linear-gradient(135deg, var(--md3-primary) 0%, #5a7fff 100%);
        }

        .btn-link {
            color: var(--md3-on-surface-variant);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            color: var(--md3-primary);
        }

        /* ===== INPUTS & FORMS - M3 ===== */
        .form-control,
        .form-select {
            background: var(--md3-surface-variant);
            border: 2px solid var(--md3-outline-variant);
            border-radius: var(--m3-radius-lg);
            padding: 12px 16px;
            color: var(--md3-on-surface);
            font-size: 1rem;
            transition: all 0.3s var(--m3-morph);
            min-height: 48px;
        }

        .form-control::placeholder {
            color: var(--md3-on-surface-variant);
            opacity: 0.7;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--md3-primary);
            box-shadow: 0 0 0 4px rgba(45, 109, 246, 0.1);
            outline: none;
            background: var(--md3-surface);
            transform: scale(1.02);
        }

        .form-control:disabled,
        .form-select:disabled {
            background: var(--md3-surface);
            color: var(--md3-on-surface-variant);
            opacity: 0.5;
        }

        .form-label {
            color: var(--md3-on-surface-variant);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        /* ===== ALERTS & BADGES - M3 ===== */
        .alert {
            border-radius: var(--m3-radius-lg);
            border: 1px solid;
            padding: 12px 16px;
            font-size: 0.9rem;
            transition: all 0.3s var(--m3-morph);
        }

        .alert-success {
            background: var(--md3-surface);
            border-color: var(--md3-success);
            color: var(--md3-success);
        }

        .alert-danger {
            background: var(--md3-surface);
            border-color: var(--md3-error);
            color: var(--md3-error);
        }

        .alert-warning {
            background: var(--md3-surface);
            border-color: var(--md3-warning);
            color: var(--md3-warning);
        }

        .alert-info {
            background: var(--md3-surface);
            border-color: var(--md3-primary);
            color: var(--md3-primary);
        }

        .badge {
            border-radius: var(--m3-radius-full);
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s var(--m3-morph);
            background-color: var(--md3-secondary-container);
            color: var(--md3-on-secondary-container);
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .bg-primary {
            background-color: var(--md3-primary) !important;
        }

        .text-primary {
            color: var(--md3-primary) !important;
        }

        .text-danger {
            color: var(--md3-error) !important;
        }

        .text-secondary {
            color: var(--md3-on-surface-variant) !important;
        }

        .text-muted {
            color: var(--md3-on-surface-variant) !important;
            opacity: 0.8;
        }

        /* ===== UTILITIES ===== */
        .fw-bold {
            font-weight: 600;
        }

        .fw-semibold {
            font-weight: 500;
        }

        .rounded-pill {
            border-radius: var(--m3-radius-full) !important;
        }

        .rounded-4 {
            border-radius: var(--m3-radius-lg) !important;
        }

        .rounded-3 {
            border-radius: var(--m3-radius-md) !important;
        }

        .shadow-sm {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        }

        .shadow-md {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
        }

        .shadow-lg {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
        }

        .shadow-xl {
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18) !important;
        }

        /* ===== LAYOUT UTILITIES ===== */
        .container-fluid {
            padding-right: 0;
            padding-left: 0;
        }

        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 1.5rem;
        }

        /* ===== TABLE STYLING - M3 ===== */
        .table {
            color: var(--md3-on-surface);
            border-collapse: collapse;
        }

        .table thead th {
            color: var(--md3-on-surface-variant);
            font-weight: 600;
            border-bottom: 2px solid var(--md3-outline-variant);
            padding: 16px;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            border-bottom: 1px solid var(--md3-outline-variant);
            padding: 16px;
            vertical-align: middle;
            transition: all 0.3s var(--m3-morph);
        }

        .table tbody tr:hover {
            background: var(--md3-surface-container);
            transform: scale(1.01);
        }

        /* ===== SPACING ===== */
        .mt-auto {
            margin-top: auto;
        }

        .border-top {
            border-top: 1px solid var(--md3-outline-variant) !important;
        }

        .border-bottom {
            border-bottom: 1px solid var(--md3-outline-variant) !important;
        }

        .border-start {
            border-left: 2px solid var(--md3-primary) !important;
        }

        .border-opacity-25 {
            opacity: 0.25;
        }

        /* ===== HELPERS ===== */
        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .h-100 {
            height: 100%;
        }

        .w-100 {
            width: 100%;
        }

        .d-flex {
            display: flex;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }

        .flex-column {
            flex-direction: column;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        .gap-4 {
            gap: 1.5rem;
        }

        .align-items-center {
            align-items: center;
        }

        .align-items-start {
            align-items: flex-start;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .justify-content-center {
            justify-content: center;
        }

        .bg-opacity-10 {
            opacity: 0.1;
        }

        /* ===== ANIMATIONS ===== */
        .fade-in {
            animation: fadeIn 0.5s var(--m3-bounce);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Morphic Scale Animation */
        @keyframes morphicScale {
            0% {
                transform: scale(0.95);
                opacity: 0;
            }
            50% {
                transform: scale(1.02);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .morphic-enter {
            animation: morphicScale 0.6s var(--m3-bounce);
        }

        /* ===== ACCOUNT SECTION ===== */
        .account-section {
            padding: 28px 16px;
            margin: 0 -8px 24px -8px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: var(--m3-radius-2xl);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.5s var(--m3-bounce);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .sidebar.collapsed .account-section {
            padding: 16px 8px;
            margin: 0 -4px 24px -4px;
            background: transparent;
            border: none;
            backdrop-filter: none;
        }

        .profile-pic-wrapper {
            position: relative;
            width: 92px;
            height: 92px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.5s var(--m3-bounce);
            padding: 4px;
            background: linear-gradient(135deg, var(--md3-primary) 0%, #5a7fff 100%);
            border-radius: 50%;
        }

        .sidebar.collapsed .profile-pic-wrapper {
            width: 48px;
            height: 48px;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--md3-surface);
            background: var(--md3-surface-variant);
            transition: all 0.4s var(--m3-transition);
        }

        .profile-pic-wrapper:hover .profile-pic {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(45, 109, 246, 0.4);
        }

        .profile-pic-wrapper::after {
            content: '\bi-camera-fill';
            font-family: 'bootstrap-icons';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 1.5rem;
        }

        .sidebar.collapsed .profile-pic-wrapper::after {
            font-size: 1rem;
        }

        .profile-pic-wrapper:hover::after {
            opacity: 1;
        }

        .account-info {
            text-align: center;
            transition: opacity 0.3s var(--m3-transition);
        }

        .sidebar.collapsed .account-info {
            display: none;
        }

        .account-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--md3-on-surface);
            margin-bottom: 2px;
        }

        .account-status {
            font-size: 0.8rem;
            color: var(--md3-on-surface-variant);
            opacity: 0.8;
        }

        /* Profile Update Animation */
        @keyframes profileFadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .profile-updated {
            animation: profileFadeIn 0.6s var(--m3-bounce);
        }

        /* ===== QUICK PREVIEW ===== */
        .preview-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(12px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.4s var(--m3-transition);
        }

        .preview-overlay.active {
            display: flex;
            opacity: 1;
        }

        .preview-content {
            background: var(--md3-surface);
            border-radius: var(--m3-radius-2xl);
            width: 90%;
            max-width: 450px;
            padding: 32px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.4);
            transform: scale(0.85) translateY(30px);
            transition: all 0.5s var(--m3-bounce);
            border: 1px solid var(--md3-outline-variant);
        }

        .preview-overlay.active .preview-content {
            transform: scale(1) translateY(0);
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .close-preview {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: var(--md3-surface-variant);
            color: var(--md3-on-surface-variant);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s var(--m3-morph);
        }

        .close-preview:hover {
            background: var(--md3-error-container);
            color: var(--md3-error);
            transform: rotate(90deg);
        }

        .apple-card[role="button"] {
            cursor: pointer;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed);
                padding: 16px 12px;
            }

            .main-content {
                margin-left: var(--sidebar-collapsed);
                padding: 16px;
            }

            .nav-text {
                display: none !important;
            }

            h1 {
                font-size: 1.75rem;
            }

            .row {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }
        }
    </style>
</head>
<body class="<?= ($_COOKIE["sidebar_hidden"] ?? "") === "true"
    ? "sidebar-hidden"
    : "" ?>">

<div class="sidebar <?= ($_COOKIE["sidebar_hidden"] ?? "") === "true"
    ? "collapsed"
    : "" ?>" id="sidebar">
    <div class="toggle-btn" id="sidebarToggle">
        <i class="bi bi-chevron-right"></i>
    </div>

    <div class="header-area">
        <div class="window-dots">
            <span class="dot red"></span>
            <span class="dot yellow"></span>
            <span class="dot green"></span>
        </div>
        <div class="logo-box">
            <i class="bi bi-intersect fs-3"></i>
        </div>
    </div>

    <?php if (isset($_SESSION["user_id"])): 
        include_once "db.php";
        $stmt = $pdo->prepare("SELECT profile_pic FROM Users WHERE id = ?");
        $stmt->execute([$_SESSION["user_id"]]);
        $user_data = $stmt->fetch();
        $profile_pic = $user_data["profile_pic"] ?? "https://ui-avatars.com/api/?name=" . urlencode($_SESSION["user_name"]) . "&background=random&color=fff";
    ?>
    <div class="account-section">
        <div class="profile-pic-wrapper" id="profilePicTrigger">
            <img src="<?= $profile_pic ?>" alt="Profile" class="profile-pic" id="sidebarProfilePic">
            <input type="file" id="profilePicInput" hidden accept="image/*">
        </div>
        <div class="account-info">
            <div class="account-name"><?= htmlspecialchars($_SESSION["user_name"]) ?></div>
            <div class="account-status">Membre Vérifié</div>
        </div>
    </div>
    <?php endif; ?>

    <nav class="flex-grow-1">
        <a href="index.php" class="nav-link active">
            <i class="bi bi-grid-1x2"></i>
            <span class="nav-text">Home</span>
        </a>
        <a href="search.php" class="nav-link">
            <i class="bi bi-search"></i>
            <span class="nav-text">Trouver</span>
        </a>
        <?php if (isset($_SESSION["user_id"])): ?>
        <a href="add_trip.php" class="nav-link">
            <i class="bi bi-plus-circle"></i>
            <span class="nav-text">Publier</span>
        </a>
        <a href="my_bookings.php" class="nav-link">
            <i class="bi bi-calendar-check"></i>
            <span class="nav-text">Réservations</span>
        </a>
        <?php else: ?>
        <a href="login.php" class="nav-link">
            <i class="bi bi-box-arrow-in-right"></i>
            <span class="nav-text">Connexion</span>
        </a>
        <a href="register.php" class="nav-link">
            <i class="bi bi-person-plus"></i>
            <span class="nav-text">S'inscrire</span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="mt-auto">
        <button class="btn btn-link nav-link mb-3 border-0 w-100" id="themeToggle">
            <i class="bi bi-moon-stars-fill"></i>
            <span class="nav-text">Mode Sombre</span>
        </button>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-left"></i>
                <span class="nav-text">Quitter</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="main-content">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Theme Logic ---
        const htmlElement = document.documentElement;
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = themeToggle ? themeToggle.querySelector('i') : null;
        const themeText = themeToggle ? themeToggle.querySelector('.nav-text') : null;

        const updateThemeUI = (theme) => {
            if (theme === 'light') {
                if (themeIcon) { themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill'); }
                if (themeText) { themeText.textContent = 'Mode Clair'; }
            } else {
                if (themeIcon) { themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill'); }
                if (themeText) { themeText.textContent = 'Mode Sombre'; }
            }
        };

        const savedTheme = localStorage.getItem('theme_preference') || 'dark';
        htmlElement.setAttribute('data-bs-theme', savedTheme);
        updateThemeUI(savedTheme);

        if (themeToggle) {
            themeToggle.addEventListener('click', function(e) {
                e.preventDefault();
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                htmlElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme_preference', newTheme);
                updateThemeUI(newTheme);
            });
        }

        // --- Sidebar Logic ---
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                const isCollapsed = sidebar.classList.contains('collapsed');
                document.cookie = `sidebar_hidden=${isCollapsed ? 'true' : 'false'}; path=/; max-age=31536000`;
            });
        }

        // --- Profile Upload Logic ---
        const profilePicTrigger = document.getElementById('profilePicTrigger');
        const profilePicInput = document.getElementById('profilePicInput');
        const sidebarProfilePic = document.getElementById('sidebarProfilePic');

        if (profilePicTrigger && profilePicInput) {
            profilePicTrigger.addEventListener('click', () => profilePicInput.click());

            profilePicInput.addEventListener('change', async function() {
                if (this.files && this.files[0]) {
                    const formData = new FormData();
                    formData.append('profile_pic', this.files[0]);

                    try {
                        const response = await fetch('upload_profile.php', {
                            method: 'POST',
                            body: formData
                        });
                        const result = await response.json();

                        if (result.success) {
                            const newUrl = result.url + '?t=' + new Date().getTime();
                            
                            // Update sidebar
                            if (sidebarProfilePic) {
                                sidebarProfilePic.src = newUrl;
                                sidebarProfilePic.classList.add('profile-updated');
                                setTimeout(() => sidebarProfilePic.classList.remove('profile-updated'), 600);
                            }
                            
                            // Update ALL profile images on the page (matching current user's name or ID)
                            // For simplicity, we'll update images with the 'user-profile-pic' class
                            document.querySelectorAll('.user-profile-pic').forEach(img => {
                                // If we're on the home page, the trip cards show the driver's pic.
                                // If the logged in user is the driver, we update it.
                                // We can use a data attribute on the card to check the user_id.
                                const card = img.closest('.apple-card');
                                const currentUserId = '<?= $_SESSION["user_id"] ?? "" ?>';
                                
                                if (card && card.dataset.userId === currentUserId) {
                                    img.src = newUrl;
                                    img.classList.remove('d-none');
                                    img.classList.add('profile-updated');
                                    
                                    // Hide placeholder if it exists
                                    const placeholder = card.querySelector('.profile-placeholder');
                                    if (placeholder) placeholder.classList.add('d-none');
                                    
                                    setTimeout(() => img.classList.remove('profile-updated'), 600);
                                }
                            });
                        } else {
                            alert('Erreur: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            });
        }
    });
</script>
