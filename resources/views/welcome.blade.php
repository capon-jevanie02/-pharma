@extends('layouts.design')
@section('content')

  <body>
    <!-- Content -->
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Login Card -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <!-- Your SVG logo goes here -->
                  </span>
                  <span class="app-brand-text demo text-body fw-bolder">Pharmaceutical</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Welcome to Pharmaceutical!</h4>
              <p class="mb-4">Please sign in to your account and start the adventure</p>
<form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
  @csrf
  <div class="mb-3">
    <label for="email" class="form-label">{{ __('Email Address') }}</label>
    <input
      type="email"
      class="form-control @error('email') is-invalid @enderror"
      id="email"
      name="email"
      value="{{ old('email') }}"
      required
      autocomplete="email"
      autofocus
      placeholder="Enter your email or username"
    />
    @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>
  <div class="mb-3 form-password-toggle">
    <div class="d-flex justify-content-between">
      <label class="form-label" for="password">{{ __('Password') }}</label>
      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}">
          <small>Forgot Password?</small>
        </a>
      @endif
    </div>
    <div class="input-group input-group-merge">
      <input
        type="password"
        id="password"
        class="form-control @error('password') is-invalid @enderror"
        name="password"
        required
        autocomplete="current-password"
        placeholder="********"
      />
      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
    </div>
    @error('password')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>
  <div class="mb-3">
    <div class="form-check">
      <input
        class="form-check-input"
        type="checkbox"
        id="remember-me"
        name="remember"
        {{ old('remember') ? 'checked' : '' }}
      />
      <label class="form-check-label" for="remember-me">Remember Me</label>
    </div>
  </div>
  <div class="mb-3">
    <button class="btn btn-primary d-grid w-100" type="submit">{{ __('Sign in') }}</button>
  </div>
</form>


              <p class="text-center">
                <span>New on our platform?</span>
                <a  href="{{ route('register') }}">
                  <span>Create an account</span>
                </a>
              </p>
            </div>
          </div>
          <!-- /Login Card -->
        </div>
      </div>
    </div>
    <!-- / Content -->

    

    @endsection