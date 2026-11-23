<x-guest-layout>
    <div class="auth-content">
        <p class="auth-eyebrow">Staff account</p>
        <h1 class="auth-heading">Create an account</h1>
        <p class="auth-description">Register to get started with Sukli POS.</p>

        <form class="auth-form" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="auth-form-grid">
                <div class="auth-field">
                    <x-input-label class="auth-label" for="username" value="Username" />
                    <x-text-input
                        id="username"
                        class="auth-input"
                        name="username"
                        type="text"
                        :value="old('username')"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error class="auth-error" :messages="$errors->get('username')" />
                </div>

                <div class="auth-field">
                    <x-input-label class="auth-label" for="full_name" value="Full name" />
                    <x-text-input
                        id="full_name"
                        class="auth-input"
                        name="full_name"
                        type="text"
                        :value="old('full_name')"
                        required
                        autocomplete="name"
                    />
                    <x-input-error class="auth-error" :messages="$errors->get('full_name')" />
                </div>

                <div class="auth-field auth-grid-span">
                    <x-input-label class="auth-label" for="email" value="Email address" />
                    <x-text-input
                        id="email"
                        class="auth-input"
                        name="email"
                        type="email"
                        :value="old('email')"
                        required
                        autocomplete="email"
                    />
                    <x-input-error class="auth-error" :messages="$errors->get('email')" />
                </div>

                <div class="auth-field">
                    <x-input-label class="auth-label" for="password" value="Password" />
                    <x-text-input
                        id="password"
                        class="auth-input"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error class="auth-error" :messages="$errors->get('password')" />
                </div>

                <div class="auth-field">
                    <x-input-label class="auth-label" for="password_confirmation" value="Confirm password" />
                    <x-text-input
                        id="password_confirmation"
                        class="auth-input"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error class="auth-error" :messages="$errors->get('password_confirmation')" />
                </div>
            </div>

            <div class="auth-actions">
                <a class="auth-link" href="{{ route('login') }}">Already registered? Log in</a>

                <x-primary-button class="auth-submit">
                    Register
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>