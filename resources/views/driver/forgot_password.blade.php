@extends('layouts.app') <!-- or use 'layouts.driver' if you have a driver layout -->

@section('content')
<div class="login-container">
  <h2>Forgot Password</h2>

  {{-- Success or Error Messages --}}
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- Forgot Password Form --}}
  <form action="{{ route('driver.password.send.otp') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="email">Registered Email Address</label>
      <input
        type="email"
        name="email"
        id="email"
        class="form-control"
        placeholder="Enter your registered email"
        value="{{ old('email') }}"
        required
      >
      @error('email')
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary mt-3">Send OTP</button>
  </form>

  {{-- Back to login --}}
  <div class="mt-3 text-center">
    <a href="{{ route('login') }}">← Back to Login</a>
  </div>
</div>
@endsection
