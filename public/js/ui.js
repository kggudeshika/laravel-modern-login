// Small progressive-enhancement helpers. The app works without JavaScript.
document.addEventListener('DOMContentLoaded', function () {
    // Show / hide password
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.parentElement.querySelector('input');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-pressed', String(show));
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    });

    // Ask before destructive actions: <form data-confirm="Delete this post?">
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!window.confirm(form.dataset.confirm)) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });
    });

    // Spinner on submit buttons
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('.btn-primary[type="submit"]');
            if (btn) btn.classList.add('is-loading');
        });
    });

    // Auto-hide flash messages
    document.querySelectorAll('.flash').forEach(function (el) {
        setTimeout(function () { el.classList.add('is-hidden'); }, 4000);
    });
});
