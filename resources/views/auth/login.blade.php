@php
  $pageConfigs = ['myLayout' => 'blank'];
@endphp

@extends('layouts/layoutMaster', ['pageConfigs' => $pageConfigs])

@section('title', 'Login')

@section('content')
<div style="max-width: 500px; position:relative ; top:60px;" class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Login -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-4">
            <a href="{{ url('/') }}" class="app-brand-link">
              <span class="app-brand-logo demo">
                @include('_partials.macros', ['height' => 20, 'withbg' => "fill: #fff;"])
              </span>
              <span class="app-brand-text demo text-heading fw-bold">
                {{ config('variables.templateName') }}
              </span>
            </a>
          </div>

          <h4 class="mb-1">Welcome! 👋</h4>
          <p class="mb-4">Sign in to your account</p>

          <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter your email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
              >
            </div>

            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  name="password"
                  placeholder="············"
                  required
                  autocomplete="current-password"
                >
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>

            <div class="d-flex justify-content-between mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember-me">
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label>
              </div>

              @if (route('password.request'))

                <a class="text-body" href="{{ route('password.request') }}">Forgot Password?</a>
              @endif
            </div>

            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>
          </form>

          {{-- <p class="text-center">
            <span>New on the platform?</span>
            <a href="{{ route('register') }}">
              <span>Create an account</span>
            </a>
          </p> --}}

        </div>
      </div>
      <!-- /Login -->
    </div>
  </div>
</div>
@endsection
