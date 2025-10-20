<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Tyre Management System | Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
  body {
    margin: 0; padding: 0; height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:
      linear-gradient(135deg, rgba(11,11,11,0.85), rgba(179,190,209,0.85)),
      url("{{ asset('assets/images/background1.jpg') }}") no-repeat center center fixed;
    background-size: cover;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    color: #fff;
    overflow: hidden;
  }

  .logo-top-left { position: absolute; top: 20px; left: 30px; width: 150px; z-index: 2; }

  .welcome-wrapper {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    z-index: 3;
    transition: opacity 0.8s ease;
  }

  .welcome-text {
    display: inline-block;
    font-size: 2.2rem;
    font-weight: 600;
    white-space: nowrap;
    color: #ffffff;
  }

  .welcome-underline {
    height: 3px;
    background: #ffffff;
    margin: 6px auto 0;
    width: 0;
    border-radius: 2px;
    transition: width 0.12s linear;
  }

  .slt { color: #1E2A78; font-weight: 700; }
  .mobitel { color: #08CB00; font-weight: 700; }

  .login-container {
    background: rgba(255,255,255,0.95);
    padding: 40px;
    border-radius: 20px;
    width: 400px;
    color: #333;
    box-shadow: 0 15px 30px rgba(0,0,0,0.4);
    opacity: 0;
    transform: translateY(50px);
    transition: all 0.8s ease;
    z-index: 2;
    position: relative;
  }

  .login-container.show {
    opacity: 1;
    transform: translateY(0);
  }

  .login-container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 700;
    color: #1e3c72;
  }

  .password-group {
    position: relative;
  }

  .password-group input {
    border-radius: 50px;
    width: 100%;
    padding-right: 45px;
  }

  .password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    cursor: pointer;
    color: #555;
  }

  .btn-primary {
    border-radius: 50px;
    width: 100%;
    font-weight: 600;
    padding: 10px;
  }

  @media (max-width: 500px){
    .login-container{ width:90%; padding:20px; }
    .welcome-text{ font-size:1.4rem; }
    .logo-top-left{ width:100px; }
  }
</style>
</head>
<body>

<img src="{{ asset('assets/images/logo3.png') }}" alt="Company Logo" class="logo-top-left">

<div class="welcome-wrapper" id="welcomeWrapper">
  <div class="welcome-text" id="welcomeText"></div>
  <div class="welcome-underline" id="welcomeUnderline"></div>
</div>

<div class="login-container" id="loginContainer">
  <h2>Login</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <form action="{{ route('login.submit') }}" method="POST">
    @csrf
    <input type="email" name="email" class="form-control mb-3" placeholder="Email" value="{{ old('email') }}" required autofocus>
    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

    <div class="password-group mb-3">
      <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
      <button type="button" class="password-toggle" id="togglePassword">
        <i class="bi bi-eye"></i>
      </button>
    </div>
    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
      <label class="form-check-label" for="remember">Remember Me</label>
    </div>

    <div class="text-center mt-2">
      <a href="{{ route('driver.password.request.form') }}" class="small">Forgot password?</a>
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
  const wrapper = document.getElementById('welcomeWrapper');
  const loginForm = document.getElementById('loginContainer');

  let partIndex = 0, charIndex = 0, currentSpan = null;
  const typingSpeed = 80, betweenPartsDelay = 100;

  function typeStep() {
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
      // After finished typing -> fade out welcome
      setTimeout(() => {
        wrapper.style.opacity = '0';
        setTimeout(() => {
          wrapper.style.display = 'none';
          loginForm.classList.add('show'); // Slide up form
        }, 200);
      },200);
    }
  }

  window.addEventListener('load', () => {
    typeStep();
  });

  // Password show/hide
  const togglePassword = document.querySelector("#togglePassword");
  const password = document.querySelector("#password");
  const icon = togglePassword.querySelector("i");

  togglePassword.addEventListener("click", function () {
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    icon.classList.toggle("bi-eye");
    icon.classList.toggle("bi-eye-slash");
  });
</script>

</body>
</html>
