@php
    $recaptchaService = app(\App\Services\RecaptchaService::class);
@endphp

@if($recaptchaService->isEnabled())
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaService->getSiteKey() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const siteKey = "{{ $recaptchaService->getSiteKey() }}";
            const form = document.querySelector('form');
            if (!form) return;

            form.addEventListener('submit', function (e) {
                if (typeof grecaptcha === 'undefined') return;

                const livewireId = form.closest('[wire\\:id]')?.getAttribute('wire:id');
                const component = livewireId && window.Livewire ? window.Livewire.find(livewireId) : null;

                if (component && !component.get('recaptcha_token')) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    grecaptcha.ready(function () {
                        grecaptcha.execute(siteKey, { action: 'admin_login' }).then(function (token) {
                            component.set('recaptcha_token', token, false);
                            form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                        }).catch(function (err) {
                            console.error('reCAPTCHA error:', err);
                            form.submit();
                        });
                    });
                }
            }, true);
        });
    </script>
    <div class="mt-3 text-center">
        <span class="text-[11px] text-gray-500 dark:text-gray-400">
            🛡️ Dilindungi oleh Google reCAPTCHA v3
        </span>
    </div>
@endif
