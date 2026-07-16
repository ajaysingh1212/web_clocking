@extends('layouts.app')

@section('title', 'Login | EEMOT Clocking PWA')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; height: 100%; }

    .login-wrap {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: #0a0f2c;
        overflow: hidden;
        z-index: 9999;
        margin: 0;
    }

    .mesh {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 20% 20%, rgba(255,122,26,0.35), transparent 40%),
            radial-gradient(circle at 80% 15%, rgba(122,58,255,0.35), transparent 45%),
            radial-gradient(circle at 30% 85%, rgba(58,123,255,0.35), transparent 45%),
            radial-gradient(circle at 85% 80%, rgba(255,58,129,0.3), transparent 45%);
        filter: blur(60px);
        animation: meshMove 12s ease-in-out infinite alternate;
        z-index: 0;
    }

    @keyframes meshMove {
        0% { transform: scale(1) translate(0,0); }
        100% { transform: scale(1.12) translate(-15px, 10px); }
    }

    .noise {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 3px 3px;
        z-index: 1;
        pointer-events: none;
    }

    .login-panel-outer {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 410px;
        border-radius: 26px;
        padding: 1.5px;
        background: linear-gradient(135deg, rgba(255,122,26,0.6), rgba(122,58,255,0.3), rgba(58,123,255,0.6));
        animation: floatIn 0.6s cubic-bezier(.2,.8,.2,1);
        box-shadow: 0 30px 80px rgba(0,0,0,0.5);
        font-family: 'Poppins', sans-serif;
    }

    @keyframes floatIn {
        from { opacity: 0; transform: translateY(30px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .login-panel {
        background: rgba(13, 18, 46, 0.92);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        padding: 42px 34px;
    }

    .brand-mark {
        width: 56px; height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, #ff7a1a, #ff3d81);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 26px rgba(255,90,60,0.45);
        margin-bottom: 20px;
    }
    .brand-mark .material-symbols-rounded {
        font-size: 28px; color: #fff;
    }

    .login-panel h1 {
        color: #fff;
        font-weight: 800;
        font-size: 1.55rem;
        letter-spacing: -0.5px;
        margin: 0 0 6px;
    }

    .login-panel .subtitle {
        color: #8892b0;
        font-size: 0.9rem;
        margin-bottom: 30px;
        line-height: 1.5;
        font-weight: 400;
    }

    .field-group {
        position: relative;
        margin-bottom: 20px;
    }

    .input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-wrap .icon-left {
        position: absolute;
        left: 16px;
        font-size: 19px;
        color: #6b7494;
        pointer-events: none;
        transition: color 0.2s ease;
        z-index: 3;
    }

    .toggle-eye {
        position: absolute;
        right: 16px;
        font-size: 19px;
        color: #6b7494;
        cursor: pointer;
        z-index: 3;
        transition: color 0.2s ease;
    }
    .toggle-eye:hover { color: #ff7a1a; }

    .form-control {
        width: 100%;
        border: 1.5px solid rgba(255,255,255,0.08);
        border-radius: 14px;
        padding: 17px 16px 8px 44px;
        font-size: 0.95rem;
        background: rgba(255,255,255,0.04);
        color: #ffffff !important;
        caret-color: #ff7a1a;
        transition: all 0.25s ease;
        font-family: 'Poppins', sans-serif;
        position: relative;
        z-index: 1;
    }

    .form-control::placeholder { color: transparent; }

    /* Fix invisible text caused by browser autofill styling */
    .form-control:-webkit-autofill,
    .form-control:-webkit-autofill:hover,
    .form-control:-webkit-autofill:focus,
    .form-control:-webkit-autofill:active {
        -webkit-text-fill-color: #ffffff !important;
        -webkit-box-shadow: 0 0 0 1000px rgba(20, 26, 58, 0.98) inset !important;
        box-shadow: 0 0 0 1000px rgba(20, 26, 58, 0.98) inset !important;
        border-color: #ff7a1a !important;
        caret-color: #ff7a1a !important;
        transition: background-color 9999s ease-in-out 0s;
    }

    .floating-label {
        position: absolute;
        left: 44px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7494;
        font-size: 0.92rem;
        pointer-events: none;
        transition: all 0.2s ease;
        background: transparent;
        z-index: 2;
    }

    .form-control:focus,
    .form-control:not(:placeholder-shown) {
        border-color: #ff7a1a;
        background: rgba(255,122,26,0.06);
        box-shadow: 0 0 0 4px rgba(255,122,26,0.1);
        outline: none;
    }

    .form-control:focus ~ .floating-label,
    .form-control:not(:placeholder-shown) ~ .floating-label {
        top: 12px;
        font-size: 0.68rem;
        color: #ff7a1a;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .form-control:focus ~ .icon-left { color: #ff7a1a; }

    .remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
    }

    .remember-check {
        display: flex;
        align-items: center;
        gap: 9px;
        cursor: pointer;
        user-select: none;
    }

    .remember-check input[type="checkbox"] {
        width: 17px; height: 17px;
        border-radius: 5px;
        accent-color: #ff7a1a;
        cursor: pointer;
    }

    .remember-check label {
        font-size: 0.85rem;
        color: #a3adc2;
        cursor: pointer;
    }

    .btn-login {
        width: 100%;
        border: none;
        border-radius: 14px;
        padding: 16px;
        font-weight: 700;
        font-size: 0.98rem;
        letter-spacing: 0.3px;
        color: #fff;
        background: linear-gradient(120deg, #ff7a1a, #ff3d81, #7a3aff);
        background-size: 220% auto;
        cursor: pointer;
        box-shadow: 0 14px 32px rgba(255,90,90,0.3);
        transition: all 0.35s ease;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        font-family: 'Poppins', sans-serif;
    }

    .btn-login:hover {
        background-position: right center;
        box-shadow: 0 18px 40px rgba(255,90,90,0.42);
        transform: translateY(-2px);
    }

    .btn-login:active { transform: translateY(0) scale(0.99); }

    .btn-login.is-loading { pointer-events: none; opacity: 0.85; }
    .btn-login .spinner-border { width: 1rem; height: 1rem; }

    .btn-login .material-symbols-rounded {
        font-size: 19px;
        transition: transform 0.3s ease;
    }
    .btn-login:hover .material-symbols-rounded {
        transform: translateX(3px);
    }

    .login-error {
        border: 1px solid rgba(255, 90, 90, 0.3);
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 18px;
        color: #ffd5d5;
        background: rgba(255, 70, 70, 0.12);
        font-size: 0.82rem;
        line-height: 1.45;
    }

    @media (max-width: 480px) {
        .login-panel { padding: 34px 24px; }
        .login-panel h1 { font-size: 1.35rem; }
    }
</style>

<div class="login-wrap">
    <div class="mesh"></div>
    <div class="noise"></div>

    <div class="login-panel-outer">
        <div class="login-panel">
            <div class="brand-mark">
                <span class="material-symbols-rounded">schedule</span>
            </div>
            <h1>EEMOT Clocking</h1>
            <p class="subtitle">Sign in to manage attendance securely.</p>

            @if($errors->any())
                <div class="login-error">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="login-error" style="border-color: rgba(80, 220, 140, 0.35); color: #d9ffe8; background: rgba(80, 220, 140, 0.12);">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="needs-loader" novalidate>
                @csrf

                <div class="field-group">
                    <div class="input-wrap">
                        <span class="material-symbols-rounded icon-left">mail</span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder=" ">
                        <label class="floating-label" for="email">Email Address</label>
                    </div>
                </div>

                <div class="field-group">
                    <div class="input-wrap">
                        <span class="material-symbols-rounded icon-left">lock</span>
                        <input id="password" name="password" type="password" class="form-control" required placeholder=" ">
                        <label class="floating-label" for="password">Password</label>
                        <span class="material-symbols-rounded toggle-eye" id="togglePassword">visibility</span>
                    </div>
                </div>

                <div class="remember-row">
                    <div class="remember-check">
                        <input type="checkbox" name="remember" value="1" id="remember" @checked(old('remember'))>
                        <label for="remember">Remember Login</label>
                    </div>
                </div>

                <button class="btn-login" type="submit">
                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                    <span>Login</span>
                    <span class="material-symbols-rounded">arrow_forward</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const toggleEye = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    if (toggleEye && passwordField) {
        toggleEye.addEventListener('click', function () {
            const isHidden = passwordField.type === 'password';
            passwordField.type = isHidden ? 'text' : 'password';
            toggleEye.textContent = isHidden ? 'visibility_off' : 'visibility';
        });
    }
</script>
@endsection