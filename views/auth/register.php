<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1>📚 Gestión Escolar</h1>
            <h2>Registro de Usuario</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="index.php?action=procesar_registro" novalidate>
                <?php echo campoTokenCSRF(); ?>
                
                <!-- NOMBRE -->
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Juan" required>
                    <small class="hint">Solo letras. Primera letra mayúscula.</small>
                </div>

                <!-- APELLIDO -->
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Ej: Pérez" required>
                    <small class="hint">Solo letras. Primera letra mayúscula.</small>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
                    <small class="hint">Solo minúsculas. Debe contener @ y .com / .es</small>
                </div>

                <!-- ROL -->
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" class="form-control" required>
                        <option value="estudiante">👨‍🎓 Estudiante</option>
                        <option value="docente">👨‍🏫 Docente</option>
                        <option value="admin">🛡️ Administrador</option>
                    </select>
                    <small class="hint">Selecciona el tipo de usuario que será esta cuenta.</small>
                </div>

                <!-- CONTRASEÑA CON VISOR Y BARRA DE SEGURIDAD -->
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        <button type="button" id="togglePassword" class="btn-eye" aria-label="Mostrar contraseña">👁️</button>
                    </div>
                    <div class="password-strength">
                        <div id="strengthBar" class="strength-bar"></div>
                        <span id="strengthText" class="strength-text">Débil</span>
                    </div>
                    <small class="hint">Mínimo 6 caracteres. Incluye letras, números y especiales (*, #, $, &, +).</small>
                </div>

                <!-- CONFIRMAR CONTRASEÑA -->
<div class="form-group">
    <label for="password_confirm">Confirmar contraseña:</label>
    <div class="password-wrapper">
        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Repite tu contraseña" required>
        <button type="button" id="toggleConfirmPassword" class="btn-eye" aria-label="Mostrar contraseña">👁️</button>
    </div>
</div>

                <button type="submit" class="btn btn-primary">Registrarse</button>
            </form>

            <p class="text-center">¿Ya tienes cuenta? <a href="index.php?action=login">Inicia sesión aquí</a></p>
            <p class="text-center"><a href="index.php">← Volver al inicio</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ==========================================
            // 1. NOMBRE Y APELLIDO: Solo letras, primera mayúscula
            // ==========================================
            function formatearNombre(campo) {
                campo.addEventListener('input', function () {
                    // Eliminar caracteres que no sean letras o espacios
                    this.value = this.value.replace(/[^a-zA-ZáéíóúñÁÉÍÓÚÑ\s]/g, '');

                    // Convertir a minúsculas y luego capitalizar primera letra de cada palabra
                    let palabras = this.value.toLowerCase().split(' ');
                    for (let i = 0; i < palabras.length; i++) {
                        if (palabras[i].length > 0) {
                            palabras[i] = palabras[i][0].toUpperCase() + palabras[i].substring(1);
                        }
                    }
                    this.value = palabras.join(' ');
                });
            }

            formatearNombre(document.getElementById('nombre'));
            formatearNombre(document.getElementById('apellido'));

            // ==========================================
            // 2. EMAIL: Solo minúsculas, debe tener @ y .com/.es
            // ==========================================
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('input', function () {
                this.value = this.value.toLowerCase().replace(/\s/g, '');
            });

            // ==========================================
// 3. CONTRASEÑA: Barra de seguridad y visor
// ==========================================
const passwordInput = document.getElementById('password');
const toggleBtn = document.getElementById('togglePassword');
const toggleConfirmBtn = document.getElementById('toggleConfirmPassword'); // ✅ Agregar
const confirmInput = document.getElementById('password_confirm');           // ✅ Agregar
const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');

            // Calcular fortaleza de la contraseña
            function calcularFortaleza(password) {
                let puntuacion = 0;
                if (password.length >= 6) puntuacion++;
                if (password.length >= 10) puntuacion++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) puntuacion++;
                if (/\d/.test(password)) puntuacion++;
                if (/[*#$&+]/.test(password)) puntuacion++;
                return puntuacion;
            }

            function actualizarBarra(password) {
                const puntos = calcularFortaleza(password);
                const porcentaje = (puntos / 5) * 100;

                let color = '#e74c3c';
                let texto = 'Débil';
                if (puntos >= 4) { color = '#27ae60'; texto = 'Fuerte'; }
                else if (puntos >= 3) { color = '#f39c12'; texto = 'Media'; }
                else if (puntos >= 2) { color = '#e67e22'; texto = 'Baja'; }

                strengthBar.style.width = porcentaje + '%';
                strengthBar.style.background = color;
                strengthText.textContent = texto;
                strengthText.style.color = color;
            }

            passwordInput.addEventListener('input', function () {
                actualizarBarra(this.value);
            });

            // Visor de contraseña PRINCIPAL
            toggleBtn.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
            });

            // Visor de contraseña CONFIRMAR
            toggleConfirmBtn.addEventListener('click', function () {
                const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
            });

            passwordInput.addEventListener('input', function () {
                actualizarBarra(this.value);
            });

            // ==========================================
            // 4. VALIDACIÓN ANTES DE ENVIAR
            // ==========================================
            document.getElementById('registerForm').addEventListener('submit', function (e) {
                let errores = [];

                const nombre = document.getElementById('nombre').value.trim();
                if (!/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/.test(nombre)) {
                    errores.push('❌ El nombre solo debe contener letras.');
                }
                if (nombre.length < 2) {
                    errores.push('❌ El nombre debe tener al menos 2 caracteres.');
                }

                const apellido = document.getElementById('apellido').value.trim();
                if (!/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/.test(apellido)) {
                    errores.push('❌ El apellido solo debe contener letras.');
                }
                if (apellido.length < 2) {
                    errores.push('❌ El apellido debe tener al menos 2 caracteres.');
                }

                const email = document.getElementById('email').value.trim();
                if (!/^[a-z0-9._%+-]+@[a-z0-9.-]+\.(com|es)$/i.test(email)) {
                    errores.push('❌ El correo debe ser válido (ej: usuario@dominio.com).');
                }

                const password = document.getElementById('password').value;
                if (password.length < 6) {
                    errores.push('❌ La contraseña debe tener al menos 6 caracteres.');
                }
                if (!/[*#$&+]/.test(password)) {
                    errores.push('❌ La contraseña debe contener al menos un carácter especial (* # $ & +).');
                }

                const confirm = document.getElementById('password_confirm').value;
                if (password !== confirm) {
                    errores.push('❌ Las contraseñas no coinciden.');
                }

                if (errores.length > 0) {
                    e.preventDefault();
                    alert(errores.join('\n'));
                }
            });

        });
    </script>
</body>
</html>