@extends('layouts.driver')

@section('title', 'Change Password')

@section('content')
<div class="container mx-auto p-6 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Change Password</h1>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <form id="passwordForm" action="{{ route('driver.password.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="current_password" class="block font-medium mb-1">Current Password</label>
            <input type="password" name="current_password" id="current_password" required class="border p-2 w-full rounded">
            <button type="button" class="toggle-password text-sm mt-1">Show</button>
        </div>

        <div class="mb-4">
            <label for="password" class="block font-medium mb-1">New Password</label>
            <input type="password" name="password" id="password" required class="border p-2 w-full rounded">
            <small id="passwordStrength" class="text-sm mt-1 block"></small>
            <button type="button" class="toggle-password text-sm mt-1">Show</button>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block font-medium mb-1">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="border p-2 w-full rounded">
            <small id="confirmMessage" class="text-sm mt-1 block"></small>
            <button type="button" class="toggle-password text-sm mt-1">Show</button>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Password</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if(input.type === 'password') {
                input.type = 'text';
                this.textContent = 'Hide';
            } else {
                input.type = 'password';
                this.textContent = 'Show';
            }
        });
    });

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthText = document.getElementById('passwordStrength');
    const confirmMessage = document.getElementById('confirmMessage');

    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const val = passwordInput.value;
        let strength = '';
        if(val.length < 6) {
            strength = 'Too short';
            strengthText.style.color = 'red';
        } else if(/[A-Z]/.test(val) && /[0-9]/.test(val) && val.length >= 8) {
            strength = 'Strong';
            strengthText.style.color = 'green';
        } else {
            strength = 'Weak';
            strengthText.style.color = 'orange';
        }
        strengthText.textContent = 'Strength: ' + strength;
    });

    // Confirm password matching
    confirmInput.addEventListener('input', function() {
        if(confirmInput.value === passwordInput.value) {
            confirmMessage.textContent = 'Passwords match';
            confirmMessage.style.color = 'green';
        } else {
            confirmMessage.textContent = 'Passwords do not match';
            confirmMessage.style.color = 'red';
        }
    });

    // Optional: prevent form submit if passwords don't match
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        if(passwordInput.value !== confirmInput.value) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });
});
</script>
@endsection
