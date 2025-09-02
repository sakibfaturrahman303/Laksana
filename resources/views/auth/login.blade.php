@extends('auth.layouts.app')

@section('content')
    <style>
        .logo-small {
            height: 120px;
            /* tinggi logo */
            width: auto;
            /* biar proporsional */
            object-fit: contain;
        }

        .logo-separator {
            font-size: 20px;
            font-weight: bold;
            color: #6b7280;
        }
    </style>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4">
                            <a href="{{ url('/') }}" class="app-brand-link gap-3">
                                <!-- Logo TVRI -->
                                <img src="{{ asset('assets/img/tvri.png') }}" alt="Logo TVRI" class="logo-small">
                                <span class="logo-separator">|</span>

                                <!-- Logo Aplikasi -->
                                <img src="{{ asset('assets/img/laksana.png') }}" alt="Logo App" class="logo-small">
                            </a>
                        </div>


                        <h4 class="mb-2">Selamat Datang</h4>
                        <p class="mb-4">Di Website Layanan Akses Sistem Peminjaman Alat!</p>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Atau Username</label>
                                <input id="login" type="text"
                                    class="form-control @error('login') is-invalid @enderror" name="login"
                                    value="{{ old('login') }}" required autofocus>

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Kata Sandi</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            <small>Kata Sandi</small>
                                        </a>
                                    @endif
                                </div>
                                <div class="input-group input-group-merge">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('password')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (session('success'))
                    showToast('success', '{{ session('success') }}', 'Sukses');
                @endif

                @if (session('error'))
                    showToast('error', '{{ session('error') }}', 'Error');
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        showToast('error', '{{ $error }}', 'Validasi');
                    @endforeach
                @endif
            });
        </script>
    @endpush
@endsection
