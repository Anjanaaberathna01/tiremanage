@extends('layouts.driver')

@section('title', 'Change Password')

@section('content')
<div class="settings-shell container px-0">
  <div class="settings-header d-flex flex-wrap align-items-center justify-content-between mb-3">
    <div>
      <h1 class="h4 mb-1 fw-semibold">Security</h1>
      <div class="text-muted">Update your password and keep your account safe.</div>
    </div>
    <a href="{{ route('driver.profile.edit') }}" class="btn btn-outline-primary"><i class="bi bi-person me-1"></i> Profile</a>
  </div>

  <div class="row g-4">
    <aside class="col-lg-3">
      <div class="card">
        <div class="card-body p-3">
          <nav class="settings-nav nav flex-column">
            <a class="nav-link" href="{{ route('driver.profile.edit') }}">Profile</a>
            <a class="nav-link active" href="{{ route('driver.password.form') }}">Security</a>
          </nav>
        </div>
      </div>
    </aside>

    <section class="col-lg-9">
      <div class="panel card">
        <div class="card-header bg-white">
          <div class="fw-semibold">Change Password</div>
          <div class="small text-muted">Use a strong password you don’t reuse elsewhere.</div>
        </div>
        <div class="card-body">
      
      @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger mb-3">
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="passwordForm" action="{{ route('driver.password.update') }}" method="POST" novalidate class="row g-3">
        @csrf

        <div class="col-12">
          <label for="current_password" class="form-label fw-semibold">Current Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password" aria-label="Toggle current password visibility">
              <i class="bi bi-eye"></i>
            </button>
            @error('current_password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="col-12">
          <label for="password" class="form-label fw-semibold">New Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-key"></i></span>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required aria-describedby="passwordHelp passwordStrengthText">
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password" aria-label="Toggle new password visibility">
              <i class="bi bi-eye"></i>
            </button>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-text" id="passwordHelp">Use at least 8 characters, including a number and an uppercase letter.</div>
          <div class="mt-2">
            <div class="progress" style="height: 6px;">
              <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small id="passwordStrengthText" class="text-muted"></small>
          </div>
        </div>

        <div class="col-12">
          <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-check2"></i></span>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation" aria-label="Toggle confirm password visibility">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          <small id="confirmMessage" class="form-text"></small>
        </div>

        <div class="col-12 d-grid">
          <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save me-1"></i> Update Password</button>
        </div>
      </form>
        </div>
      </div>
    </section>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.getAttribute('data-target');
      const input = document.getElementById(id);
      const icon = this.querySelector('i');
      if (!input) return;
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      if (icon) icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
  });

  const passwordInput = document.getElementById('password');
  const confirmInput = document.getElementById('password_confirmation');
  const strengthBar = document.getElementById('passwordStrengthBar');
  const strengthText = document.getElementById('passwordStrengthText');
  const confirmMessage = document.getElementById('confirmMessage');

  function evaluateStrength(val) {
    let score = 0;
    if (val.length >= 8) score += 1;
    if (/[A-Z]/.test(val)) score += 1;
    if (/[0-9]/.test(val)) score += 1;
    if (/[^A-Za-z0-9]/.test(val)) score += 1;
    return score; // 0..4
  }

  function renderStrength(val) {
    const score = evaluateStrength(val);
    const pct = (score / 4) * 100;
    let cls = 'bg-danger';
    let label = 'Too short';
    if (score <= 1) { cls = 'bg-danger'; label = 'Weak'; }
    if (score === 2) { cls = 'bg-warning'; label = 'Fair'; }
    if (score === 3) { cls = 'bg-info'; label = 'Good'; }
    if (score === 4) { cls = 'bg-success'; label = 'Strong'; }
    strengthBar.className = 'progress-bar ' + cls;
    strengthBar.style.width = pct + '%';
    strengthText.textContent = val ? ('Strength: ' + label) : '';
  }

  if (passwordInput) {
    passwordInput.addEventListener('input', function () { renderStrength(passwordInput.value); });
  }

  function renderConfirmState() {
    if (!confirmInput || !passwordInput) return;
    if (!confirmInput.value) { confirmMessage.textContent = ''; return; }
    if (confirmInput.value === passwordInput.value) {
      confirmMessage.textContent = 'Passwords match';
      confirmMessage.className = 'form-text text-success';
    } else {
      confirmMessage.textContent = 'Passwords do not match';
      confirmMessage.className = 'form-text text-danger';
    }
  }

  if (confirmInput) {
    confirmInput.addEventListener('input', renderConfirmState);
    passwordInput && passwordInput.addEventListener('input', renderConfirmState);
  }

  document.getElementById('passwordForm').addEventListener('submit', function (e) {
    if (passwordInput && confirmInput && passwordInput.value !== confirmInput.value) {
      e.preventDefault();
    }
  });
});
</script>
@endpush

<style>
/* Modern professional, aligned with profile */
:root { --surface: #ffffff; --border: rgba(0,0,0,.08); --accent: #0d6efd; }
.settings-header { padding: .25rem 0 1rem; border-bottom: 1px solid var(--border); }
.card { border-radius: 14px; border: 1px solid var(--border); background: var(--surface); }
.card-header { border-bottom: 1px solid var(--border); }
.panel { box-shadow: 0 6px 24px rgba(2,6,23,.06); }
.form-control:focus { box-shadow: 0 0 0 .2rem rgba(13,110,253,.12); border-color: #86b7fe; border-radius: .6rem; }
.settings-nav .nav-link { color: #0f172a; border-radius: 10px; padding: .5rem .75rem; transition: background .15s ease, color .15s ease, border-color .15s ease; }
.settings-nav .nav-link:hover { background: #f0f6ff; color: #0b5ed7; }
.settings-nav .nav-link.active { background: #e7f1ff; color: #0d6efd; border: 1px solid rgba(13,110,253,.25); }
</style>
@endsection
