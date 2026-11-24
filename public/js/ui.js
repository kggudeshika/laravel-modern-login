// Small progressive-enhancement helpers. Every page still works without JavaScript.
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

    // Caps Lock warning on password fields
    document.querySelectorAll('input[type="password"]').forEach(function (input) {
        var field = input.closest('.field');
        if (!field) return;
        var hint = document.createElement('p');
        hint.className = 'caps-hint';
        hint.textContent = 'Caps Lock is on';
        hint.hidden = true;
        field.appendChild(hint);
        ['keydown', 'keyup'].forEach(function (evt) {
            input.addEventListener(evt, function (e) {
                if (e.getModifierState) hint.hidden = !e.getModifierState('CapsLock');
            });
        });
        input.addEventListener('blur', function () { hint.hidden = true; });
    });

    // Password strength meter: <input data-strength="#password-strength">
    document.querySelectorAll('input[data-strength]').forEach(function (input) {
        var box = document.querySelector(input.dataset.strength);
        if (!box) return;
        var labels = ['Too weak', 'Weak', 'Fair', 'Good', 'Strong'];
        var update = function () {
            var v = input.value;
            var rules = {
                length: v.length >= 8,
                letter: /[a-z]/i.test(v),
                number: /\d/.test(v),
                mixed: /[a-z]/.test(v) && /[A-Z]/.test(v),
                symbol: /[^a-z0-9]/i.test(v)
            };
            Object.keys(rules).forEach(function (key) {
                var li = box.querySelector('[data-rule="' + key + '"]');
                if (li) li.classList.toggle('is-met', rules[key]);
            });
            var score = 0;
            if (rules.length && rules.letter && rules.number) score = 2;
            else if (v.length > 0) score = 1;
            if (score >= 2 && rules.mixed) score++;
            if (score >= 2 && rules.symbol) score++;
            if (score >= 2 && v.length >= 12) score = Math.min(4, score + 1);
            box.dataset.score = v.length ? score : '';
            box.querySelector('.strength-label strong').textContent = v.length ? labels[score] : '—';
        };
        input.addEventListener('input', update);
        update();
    });

    // "Passwords match" hint: <input data-match="password">
    document.querySelectorAll('input[data-match]').forEach(function (input) {
        var other = document.getElementById(input.dataset.match);
        var hint = document.getElementById('match-hint');
        if (!other || !hint) return;
        var update = function () {
            if (!input.value) { hint.hidden = true; return; }
            var ok = input.value === other.value;
            hint.hidden = false;
            hint.textContent = ok ? '✓ Passwords match' : 'Passwords do not match yet';
            hint.className = 'match-hint ' + (ok ? 'is-ok' : 'is-bad');
        };
        input.addEventListener('input', update);
        other.addEventListener('input', update);
    });

    // Demo account buttons on the login page
    document.querySelectorAll('.demo-fill').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var email = document.getElementById('email');
            var password = document.getElementById('password');
            if (email) email.value = btn.dataset.email;
            if (password) password.value = btn.dataset.password;
            if (password) password.focus();
        });
    });

    // Profile picture: preview the chosen image before uploading
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        var target = document.querySelector(input.dataset.preview);
        var nameEl = document.querySelector(input.dataset.filename || '');
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file) return;
            if (nameEl) nameEl.textContent = file.name;
            if (target) {
                var img = document.createElement('img');
                img.className = target.className.replace(/\bavatar-img\b/, '') + ' avatar-img';
                img.alt = 'New profile picture preview';
                img.src = URL.createObjectURL(file);
                img.id = target.id;
                target.replaceWith(img);
                target = img;
            }
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

    // Admin sidebar toggle on small screens
    var sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-open');
        });
    }

    // Auto-hide flash messages
    document.querySelectorAll('.flash').forEach(function (el) {
        setTimeout(function () { el.classList.add('is-hidden'); }, 4000);
    });
});
