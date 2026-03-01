<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PageantPro – @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pageant.css') }}">
</head>
<body>
<div class="app-bg">
<div class="main-layout">

    {{-- ═══════════════════════════════
         SIDEBAR
    ═══════════════════════════════ --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <span class="logo-icon">👑</span> PageantPro
            </div>
            <div class="sidebar-event">Miss Philippines 2025</div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-label">Main</span>

            {{-- Admin sees everything --}}
            @if(auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
                <a href="{{ route('contestants.index') }}"
                   class="nav-item {{ request()->routeIs('contestants.*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span> Contestants
                </a>
                <a href="{{ route('scoring.index') }}"
                   class="nav-item {{ request()->routeIs('scoring.*') ? 'active' : '' }}">
                    <span class="nav-icon">📝</span> Scoring
                </a>
                <a href="{{ route('leaderboard.index') }}"
                   class="nav-item {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏆</span> Leaderboard
                </a>

                <span class="nav-section-label">Operations</span>
                <a href="{{ route('tabulation.index') }}"
                   class="nav-item {{ request()->routeIs('tabulation.*') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span> Tabulation
                </a>
            @endif

            {{-- Judge sees scoring + leaderboard only --}}
            @if(auth()->user()->isJudge())
                <a href="{{ route('scoring.index') }}"
                   class="nav-item {{ request()->routeIs('scoring.*') ? 'active' : '' }}">
                    <span class="nav-icon">📝</span> Score Contestants
                </a>
                <a href="{{ route('leaderboard.index') }}"
                   class="nav-item {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏆</span> Results
                </a>
            @endif

            {{-- Tabulator sees tabulation + leaderboard --}}
            @if(auth()->user()->isTabulator())
                <a href="{{ route('tabulation.index') }}"
                   class="nav-item {{ request()->routeIs('tabulation.*') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span> Tabulation
                </a>
                <a href="{{ route('leaderboard.index') }}"
                   class="nav-item {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏆</span> Results
                </a>
            @endif

            {{-- Audience sees leaderboard only --}}
            @if(auth()->user()->role === 'audience')
                <a href="{{ route('leaderboard.index') }}"
                   class="nav-item {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏆</span> Live Results
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="user-pill">
                <div class="user-avatar">
                    {{-- First two letters of their name --}}
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>

                {{-- Logout button --}}
                <form method="POST" action="{{ route('auth.logout') }}" style="margin-left:auto">
                    @csrf
                    <button type="submit" title="Logout"
                        style="background:none;border:none;cursor:pointer;font-size:18px;opacity:0.4;color:white">
                        ⏻
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════ --}}
    <div class="main-content">

        {{-- TOPBAR --}}
        <header class="topbar">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:13px;color:rgba(255,255,255,0.35)">PageantPro ›</span>
                <span style="font-size:14px;font-weight:600;color:rgba(255,255,255,0.85)">
                    @yield('title', 'Dashboard')
                </span>
            </div>
            <div class="tb-right">
                <span class="live-dot">LIVE · Segment 4 of 5</span>
                <div class="event-badge">🎭 Miss Philippines 2025</div>
                <div class="notif-btn">🔔<span class="notif-dot"></span></div>
            </div>
        </header>

        {{-- FLASH MESSAGE (success/error) --}}
        @if(session('success'))
            <div style="margin:16px 32px 0;padding:12px 18px;background:rgba(46,204,113,0.15);
                        border:1px solid rgba(46,204,113,0.3);border-radius:10px;
                        color:#2ECC71;font-size:13px;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="margin:16px 32px 0;padding:12px 18px;background:rgba(231,76,60,0.15);
                        border:1px solid rgba(231,76,60,0.3);border-radius:10px;
                        color:#E74C3C;font-size:13px;">
                ✕ {{ session('error') }}
            </div>
        @endif

        {{-- PAGE CONTENT --}}
        {{-- Each child blade fills this slot using @section('content') --}}
        <div class="page-body">
            @yield('content')
        </div>

    </div>
</div>
</div>

<script src="{{ asset('js/pageant.js') }}"></script>
</body>
</html>
