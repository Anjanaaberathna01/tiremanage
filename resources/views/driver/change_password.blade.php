@extends('layouts.driver')

@section('title', 'Change Password')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6 col-xl-5">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Change Password</h5>
        </div>
        <div class="card-body">
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <form id="passwordForm" action="{{ route('driver.password.update') }}" method="POST" novalidate>
            @csrf

            <div class="mb-3">
              <label for="current_password" class="form-label">Current Password</label>
              <div class="input-group">
                <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                <button type="button" class="btn btn-outline-secondary" data-toggle-target="#current_password" aria-label="Show password">
                  <i class="bi bi-eye"></i>
                </button>
                @error('current_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <div class="input-group">
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" minlength="8" required>
                <button type="button" class="btn btn-outline-secondary" data-toggle-target="#password" aria-label="Show password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              <div class="mt-2">
                <div class="progress" style="height: 6px;">
                  <div id="pwStrengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small id="passwordStrength" class="text-muted"></small>
              </div>
              @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm New Password</label>
              <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                <button type="button" class="btn btn-outline-secondary" data-toggle-target="#password_confirmation" aria-label="Show password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              <small id="confirmMessage" class="d-block mt-1"></small>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Toggle password visibility buttons
  document.querySelectorAll('[data-toggle-target]').forEach(btn => {
    btn.addEventListener('click', function(){
      const target = document.querySelector(this.getAttribute('data-toggle-target'));
      const icon = this.querySelector('i');
      if (!target) return;
      const isPw = target.type === 'password';
      target.type = isPw ? 'text' : 'password';
      if (icon) {
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
      }
      this.setAttribute('aria-label', isPw ? 'Hide password' : 'Show password');
    });
  });

  const passwordInput = document.getElementById('password');
  const confirmInput = document.getElementById('password_confirmation');
  const strengthText = document.getElementById('passwordStrength');
  const strengthBar = document.getElementById('pwStrengthBar');
  const confirmMessage = document.getElementById('confirmMessage');

  function calcStrength(pw){
    let score = 0;
    if (pw.length >= 8) score += 25;
    if (/[A-Z]/.test(pw)) score += 25;
    if (/[a-z]/.test(pw)) score += 15;
    if (/[0-9]/.test(pw)) score += 20;
    if (/[^A-Za-z0-9]/.test(pw)) score += 15;
    return Math.min(score, 100);
  }

  passwordInput.addEventListener('input', function() {
    const val = passwordInput.value || '';
    const score = calcStrength(val);
    strengthBar.style.width = score + '%';
    let label = 'Weak';
    let cls = 'bg-danger';
    if (score >= 75) { label = 'Strong'; cls = 'bg-success'; }
    else if (score >= 45) { label = 'Medium'; cls = 'bg-warning'; }
    strengthBar.className = 'progress-bar ' + cls;
    strengthText.textContent = 'Strength: ' + label;
  });

  confirmInput.addEventListener('input', function() {
    if(confirmInput.value === passwordInput.value) {
      confirmMessage.textContent = 'Passwords match';
      confirmMessage.className = 'text-success';
    } else {
      confirmMessage.textContent = 'Passwords do not match';
      confirmMessage.className = 'text-danger';
    }
  });

  document.getElementById('passwordForm').addEventListener('submit', function(e) {
    if(passwordInput.value !== confirmInput.value) {
      e.preventDefault();
      confirmMessage.textContent = 'Passwords do not match';
      confirmMessage.className = 'text-danger';
      confirmInput.focus();
    }
  });
});
</script>
@endsection
