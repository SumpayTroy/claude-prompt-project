<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PageantPro – Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pageant.css') }}">
</head>
<body>
<div class="app-bg">

<div class="login-page">
    <div class="login-bg-decor"></div>
    <div class="login-theme-toggle">
    <button class="theme-toggle" onclick="toggleTheme()" id="theme-toggle">☀️</button>
    </div>
    <div class="login-card">
        <div class="login-crown">👑</div>
        <div class="login-title">PageantPro</div>
        <div class="login-subtitle">Judging & Scoring System</div>

        {{-- ── VALIDATION ERRORS ── --}}
        {{-- @error checks if a field has a validation error from the controller --}}
        @if($errors->any())
            <div style="background:rgba(231,76,60,0.15);border:1px solid rgba(231,76,60,0.3);
                        border-radius:10px;padding:12px 16px;margin-bottom:20px;
                        color:#E74C3C;font-size:13px;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ── LOGIN FORM ── --}}
        {{-- method="POST" sends data to the server --}}
        {{-- action points to our login route --}}
        <form method="POST" action="{{ route('auth.login') }}">
            {{-- @csrf is required for all POST forms in Laravel — security token --}}
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                {{-- old('email') refills the field if validation fails --}}
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-input"
                    placeholder="admin@pageant.com"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-input"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>

        {{-- Test accounts hint (remove in production) --}}
        <div style="margin-top:24px;padding:14px;background:rgba(255,255,255,0.03);
                    border-radius:10px;border:1px dashed rgba(212,168,67,0.15)">
            <div style="font-size:11px;color:var(--gold);letter-spacing:1px;
                        text-transform:uppercase;margin-bottom:8px;opacity:0.7">
                Test Accounts (password: password)
            </div>
            <div style="font-size:12px;color:rgba(255,255,255,0.4);line-height:1.8">
                admin@pageant.com &nbsp;·&nbsp; Admin<br>
                judge1@pageant.com &nbsp;·&nbsp; Judge<br>
                tabulator@pageant.com &nbsp;·&nbsp; Tabulator<br>
                audience@pageant.com &nbsp;·&nbsp; Audience
            </div>
        </div>
    </div>
</div>

</div>
 <script>
        document.addEventListener('DOMContentLoaded', () => {
            const saved = localStorage.getItem('theme') || 'dark';
            if (saved === 'light') document.body.classList.add('light-mode');
            document.getElementById('theme-toggle').textContent = saved === 'light' ? '🌙' : '☀️';
        });

        function toggleTheme() {
            const isLight = document.body.classList.toggle('light-mode');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
            document.getElementById('theme-toggle').textContent = isLight ? '🌙' : '☀️';
        }
    </script>
</body>
</html>
