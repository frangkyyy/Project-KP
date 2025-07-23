@extends('layouts.app')

@section('content')
    <section class="vh-100" style="background-color: #f4f5f7;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12">
                    <div class="card shadow-lg" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 d-none d-md-block">
                                <img src="https://img2.storyblok.com/1200x600/filters:focal(600x353:601x354):quality(90)/f/60990/1200x666/a36f27c1c8/les-dan-harga-kursus-di-ef-untuk-anak-dan-remaja.jpg" alt="Login Illustration" class="img-fluid" style="border-radius: 1rem 0 0 1rem; height: 100%; object-fit: cover;" />
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="card-body p-5 text-black">

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="text-center mb-4">
                                            <h3 class="fw-bold">{{ __('Login') }}</h3>
                                            <p class="text-muted">Access your account</p>
                                        </div>

                                        @if ($errors->has('email') || $errors->has('password'))
                                            <div class="alert alert-danger" role="alert">
                                                <strong>Email address atau password salah</strong>
                                            </div>
                                        @endif

                                        {{-- Menampilkan pesan sukses jika login berhasil --}}
                                        @if (session('success'))
                                            <div class="alert alert-success" role="alert">
                                                <strong>{{ session('success') }}</strong>
                                            </div>
                                        @endif

                                        <div class="form-group mb-4">
                                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="password" class="form-label">{{ __('Password') }}</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                                            @enderror
                                        </div>

                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>

                                        <div class="d-grid gap-2 mb-3">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                {{ __('Login') }}
                                            </button>
                                        </div>

{{--                                        <div class="text-center">--}}
{{--                                            @if (Route::has('password.request'))--}}
{{--                                                <a class="text-decoration-none" href="{{ route('password.request') }}">--}}
{{--                                                    {{ __('Forgot Your Password?') }}--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}

{{--                                        <div class="text-center mt-4">--}}
{{--                                            <p class="mb-0 text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>--}}
{{--                                        </div>--}}

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
