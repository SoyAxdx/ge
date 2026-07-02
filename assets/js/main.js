// ============================================
// GESTIÓN ESCOLAR - JavaScript
// ============================================

document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // TEMA OSCURO / CLARO
    // ============================================

    // Crear botón si no existe
    let themeBtn = document.getElementById('themeToggle');
    if (!themeBtn) {
        themeBtn = document.createElement('button');
        themeBtn.id = 'themeToggle';
        themeBtn.className = 'theme-toggle';
        themeBtn.setAttribute('aria-label', 'Cambiar tema');
        document.body.appendChild(themeBtn);
    }

    // Detectar tema guardado o preferencia del sistema
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        document.body.classList.add('dark-mode');
        themeBtn.textContent = '☀️';
    } else {
        document.body.classList.remove('dark-mode');
        themeBtn.textContent = '🌙';
    }

    // Cambiar tema al hacer clic
    themeBtn.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        themeBtn.textContent = isDark ? '☀️' : '🌙';
    });

    // ============================================
    // CSRF: Auto-generar token si no existe
    // ============================================
    const csrfField = document.querySelector('input[name="csrf_token"]');
    if (csrfField && !csrfField.value) {
        const token = Array.from(crypto.getRandomValues(new Uint8Array(32)))
            .map(b => b.toString(16).padStart(2, '0'))
            .join('');
        csrfField.value = token;
    }

    // ============================================
    // CONFIRMAR ELIMINACIÓN
    // ============================================
    document.querySelectorAll('.btn-eliminar, .eliminar-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de eliminar este registro?\nEsta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });

    // ============================================
    // AUTO-CERRAR ALERTAS DESPUÉS DE 5 SEGUNDOS
    // ============================================
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ============================================
    // VISOR DE CONTRASEÑA (login/register)
    // ============================================
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = document.querySelector(this.dataset.target);
            if (input) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            }
        });
    });

});