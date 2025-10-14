@extends('layouts.app')

@section('content')
<div class="login-container">
  <h2>Reset Password</h2>

  {{-- Success or error messages --}}
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- Reset Password Form --}}
  <form action="{{ route('driver.password.reset') }}" method="POST">
    @csrf

    <div class="form-group">
      <label for="email">Email</label>
      <input
        type="email"
        name="email"
        id="email"
        class="form-control"
        value="{{ old('email', $email ?? '') }}"
        readonly
      >
    </div>

    <div class="form-group mt-3">
      <label for="password">New Password</label>
      <input
        type="password"
        name="password"
        id="password"
        class="form-control"
        placeholder="Enter new password"
        required
        autocomplete="new-password"
      >
      @error('password')
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group mt-3">
      <label for="password_confirmation">Confirm Password</label>
      <input
        type="password"
        name="password_confirmation"
        id="password_confirmation"
        class="form-control"
        placeholder="Confirm your new password"
        required
        autocomplete="new-password"
      >
      @error('password_confirmation')
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary mt-4 w-100">Reset Password</button>
  </form>

  <div class="mt-3 text-center">
    <a href="{{ route('login') }}">← Back to Login</a>
  </div>
</div>
@endsection
