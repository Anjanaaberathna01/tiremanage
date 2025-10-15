@extends('layouts.driver')

@section('title', 'Change Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Change Password</h5>
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

                <form id="passwordForm" action="{{ route('driver.password.update') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password" aria-label="Toggle current password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror" required aria-describedby="passwordHelp passwordStrengthText">
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

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation" aria-label="Toggle confirm password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <small id="confirmMessage" class="form-text"></small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
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
        passwordInput.addEventListener('input', function () {
            renderStrength(passwordInput.value);
        });
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
@endsection
