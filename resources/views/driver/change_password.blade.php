@extends('layouts.driver')

@section('title', 'Change Password')

@section('content')
<div class="container" style="max-width:500px; margin: 2rem auto; padding:1.5rem; border:1px solid #ddd; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
    <h1 style="font-size:1.75rem; margin-bottom:1.5rem; font-weight:bold;">Change Password</h1>

    @if(session('success'))
        <div style="background-color:#d4edda; color:#155724; padding:0.75rem; border-radius:5px; margin-bottom:1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background-color:#f8d7da; color:#721c24; padding:0.75rem; border-radius:5px; margin-bottom:1rem;">{{ session('error') }}</div>
    @endif

    <form id="passwordForm" action="{{ route('driver.password.update') }}" method="POST" style="display:flex; flex-direction:column; gap:1.25rem;">
        @csrf

        <!-- Current Password -->
        <div style="position:relative;">
            <label for="current_password" style="display:block; margin-bottom:0.25rem;">Current Password</label>
            <input type="password" name="current_password" id="current_password" required style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;">
            <button type="button" class="toggle-password" style="position:absolute; right:10px; top:32px; font-size:0.875rem; cursor:pointer;">Show</button>
        </div>

        <!-- New Password -->
        <div style="position:relative;">
            <label for="password" style="display:block; margin-bottom:0.25rem;">New Password</label>
            <input type="password" name="password" id="password" required style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;">
            <p id="passwordStrength" style="margin-top:0.25rem; font-size:0.875rem;"></p>
            <button type="button" class="toggle-password" style="position:absolute; right:10px; top:32px; font-size:0.875rem; cursor:pointer;">Show</button>
        </div>

        <!-- Confirm Password -->
        <div style="position:relative;">
            <label for="password_confirmation" style="display:block; margin-bottom:0.25rem;">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required style="width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:5px;">
            <p id="confirmMessage" style="margin-top:0.25rem; font-size:0.875rem;"></p>
            <button type="button" class="toggle-password" style="position:absolute; right:10px; top:32px; font-size:0.875rem; cursor:pointer;">Show</button>
        </div>

        <button type="submit" style="width:100%; padding:0.5rem; background-color:#007bff; color:white; border:none; border-radius:5px; font-weight:bold; cursor:pointer;">Update Password</button>
    </form>
</div>

<script>
// Password toggle
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = btn.previousElementSibling;
        if(input.type === 'password') {
            input.type = 'text';
            btn.textContent = 'Hide';
        } else {
            input.type = 'password';
            btn.textContent = 'Show';
        }
    });
});

// Strength and match
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('password_confirmation');
const strengthText = document.getElementById('passwordStrength');
const confirmMessage = document.getElementById('confirmMessage');

passwordInput.addEventListener('input', () => {
    const val = passwordInput.value;
    let strength = '';
    let color = '';

    if(val.length < 6){
        strength = 'Too short';
        color = 'red';
    } else if(/[A-Z]/.test(val) && /[0-9]/.test(val) && val.length >= 8){
        strength = 'Strong';
        color = 'green';
    } else {
        strength = 'Weak';
        color = 'orange';
    }

    strengthText.textContent = `Strength: ${strength}`;
    strengthText.style.color = color;
});

confirmInput.addEventListener('input', () => {
    if(confirmInput.value === passwordInput.value){
        confirmMessage.textContent = 'Passwords match';
        confirmMessage.style.color = 'green';
    } else {
        confirmMessage.textContent = 'Passwords do not match';
        confirmMessage.style.color = 'red';
    }
});

// Prevent submit if passwords don't match
document.getElementById('passwordForm').addEventListener('submit', e => {
    if(passwordInput.value !== confirmInput.value){
        e.preventDefault();
        confirmMessage.textContent = 'Passwords do not match!';
        confirmMessage.style.color = 'red';
    }
});
</script>
@endsection
