<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Tyre Management System | Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  body{
    margin:0; padding:0; height:100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:
      linear-gradient(135deg, rgba(11,11,11,0.85), rgba(179,190,209,0.85)),
      url("{{ asset('assets/images/background1.jpg') }}") no-repeat center center fixed;
    background-size: cover;
    display:flex; justify-content:center; align-items:center; flex-direction:column;
    color:#fff; overflow:hidden;
  }

  .logo-top-left{ position:absolute; top:20px; left:30px; width:150px; z-index:2; }

  .welcome-wrapper{
    position:absolute; top:60px; left:50%; transform:translateX(-50%);
    text-align:center; z-index:2;
  }

  .welcome-text{
    display:inline-block;
    font-size:2rem; font-weight:600;
    white-space:nowrap;
    color: #ffffff;
  }

  .welcome-underline{
    height:3px;
    background:#ffffff;
    margin:6px auto 0;
    width:0;
    border-radius:2px;
    box-shadow:1px 1px 5px rgba(0,0,0,0.6);
    transform-origin:left center;
    transition:width 0.12s linear;
  }

  /* colored parts */
  .slt{ color:#1E2A78; font-weight:700; }
  .mobitel{ color:#08CB00; font-weight:700; }

  .system-info{ text-align:center; z-index:2; margin-top:100px; }
  .system-info h1{ font-size:2.5rem; font-weight:800; text-shadow:2px 2px 10px rgba(0,0,0,0.6); margin-bottom:10px; color:#fff;}
  .system-info p{ font-size:1rem; margin:2px 0; color:#d0e6ff; }

  .login-container{
    background:rgba(255,255,255,0.95); padding:40px; border-radius:20px;
    width:400px; color:#333; box-shadow:0 15px 30px rgba(0,0,0,0.4); z-index:2; margin-top: 50px;
  }
  .login-container h2{ text-align:center; margin-bottom:25px; font-weight:700; color:#1e3c72; }
  .form-control{ border-radius:50px; margin-bottom:15px; padding:10px 20px; }
  .btn-primary{ border-radius:50px; width:100%; font-weight:600; padding:10px; }

  /* Remember Me checkbox */
  .form-check-label {
    user-select: none;
    font-weight: 500;
  }

  @media (max-width:500px){
    .login-container{ width:90%; padding:20px; }
    .system-info h1{ font-size:1.8rem; }
    .welcome-text{ font-size:1.4rem; top:40px; }
    .logo-top-left{ width:70px; }
  }
</style>
</head>
<body>

<img src="{{ asset('assets/images/logo3.png') }}" alt="Company Logo" class="logo-top-left">

<div class="welcome-wrapper">
  <div class="welcome-text" id="welcomeText"></div>
  <div class="welcome-underline" id="welcomeUnderline"></div>
</div>

<div class="system-info">
  <h1>Tyre Management System</h1>
  <p>Smart Management for SLT Mobile Vehicles</p>
  <p>© 2025 SLT Mobile Systems</p>
</div>

<div class="login-container">
  <h2>Login</h2>
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <form action="{{ route('login.submit') }}" method="POST">
    @csrf
    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

    <input type="password" name="password" class="form-control" placeholder="Password" required>
    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

    <!-- Remember Me Checkbox -->
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
      <label class="form-check-label" for="remember">Remember Me</label>
    </div>

    <button type="submit" class="btn btn-primary mt-2">Login</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const parts = [
    { text: "Welcome ", className: "" },
    { text: "SLT", className: "slt" },
    { text: "MOBITEL", className: "mobitel" },
  ];

  const container = document.getElementById('welcomeText');
  const underline = document.getElementById('welcomeUnderline');

  let partIndex = 0;
  let charIndex = 0;
  let currentSpan = null;
  const typingSpeed = 100;
  const betweenPartsDelay = 150;
  const restartDelay = 1500;

  function typeStep(){
    if (partIndex < parts.length) {
      const part = parts[partIndex];
      if (!currentSpan) {
        currentSpan = document.createElement('span');
        if (part.className) currentSpan.className = part.className;
        container.appendChild(currentSpan);
      }

      charIndex++;
      currentSpan.textContent = part.text.slice(0, charIndex);

      underline.style.width = Math.ceil(container.getBoundingClientRect().width) + 'px';

      if (charIndex < part.text.length) {
        setTimeout(typeStep, typingSpeed);
      } else {
        currentSpan = null;
        charIndex = 0;
        partIndex++;
        setTimeout(typeStep, betweenPartsDelay);
      }
    } else {
      setTimeout(() => {
        container.innerHTML = '';
        underline.style.width = '0px';
        partIndex = 0;
        charIndex = 0;
        currentSpan = null;
        setTimeout(typeStep, 500);
      }, restartDelay);
    }
  }

  window.addEventListener('resize', () => {
    underline.style.width = Math.ceil(container.getBoundingClientRect().width) + 'px';
  });

  window.addEventListener('load', () => { typeStep(); });
</script>
</body>
</html>
