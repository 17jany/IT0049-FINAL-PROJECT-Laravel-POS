<x-guest-layout>
    <div class="auth-content">
        <p class="auth-eyebrow">Staff sign in</p>
        <h1 class="auth-heading">Welcome back</h1>
        <p class="auth-description">Sign in to manage your store.</p>

        <x-auth-session-status class="auth-status" :status="session('status')" />

        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth-field">
                <x-input-label class="auth-label" for="email" value="Email address" />
                <x-text-input
                    id="email"
                    class="auth-input"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                />
                <x-input-error class="auth-error" :messages="$errors->get('email')" />
            </div>

            <div class="auth-field">
                <x-input-label class="auth-label" for="password" value="Password" />
                <x-text-input
                    id="password"
                    class="auth-input"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />
                <x-input-error class="auth-error" :messages="$errors->get('password')" />
            </div>

            <div class="auth-links-row">
                <label class="auth-remember" for="remember_me">
                    <input
                        id="remember_me"
                        class="auth-checkbox"
                        type="checkbox"
                        name="remember"
                    >
                    <span>Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <div class="auth-actions">
                <span></span>
                <x-primary-button class="auth-submit">
                    Log in
                </x-primary-button>
            </div>
        </form>

        @if (Route::has('register'))
            <p class="auth-switch">
                Need an account?
                <a class="auth-link" href="{{ route('register') }}">Register here</a>
            </p>
        @endif
    </div>
</x-guest-layout>