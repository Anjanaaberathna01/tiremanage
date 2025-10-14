@extends('layouts.app')

@section('content')
<div class="login-container">
  <h2>Enter OTP</h2>

  {{-- Success or error messages --}}
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- OTP Verification Form --}}
  <form action="{{ route('driver.password.verify') }}" method="POST">
    @csrf

    <div class="form-group mt-2">
      <label for="email">Email</label>
      <input
        type="email"
        name="email"
        id="email"
        class="form-control"
        value="{{ old('email', $email ?? '') }}"
        readonly
      >
      @error('email')
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group mt-2">
      <label for="otp">6-digit OTP</label>
      <input
        type="text"
        name="otp"
        id="otp"
        class="form-control"
        placeholder="Enter OTP"
        required
      >
      @error('otp')
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary mt-3">Verify OTP</button>
  </form>

  <div class="mt-3 text-center">
    <a href="{{ route('driver.password.request.form') }}">← Request new OTP</a>
  </div>
</div>
@endsection
