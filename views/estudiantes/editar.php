<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php $estudiante = $estudiante ?? []; ?>
    <div class="dashboard-container" style="max-width:600px;">
        <div class="dashboard-header">
            <h1>✏️ Editar Estudiante</h1>
            <a href="index.php?action=estudiantes" class="btn btn-secondary" style="display:inline-block; width:auto; padding:5px 15px;">← Volver</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form id="formEstudiante" method="POST" action="index.php?action=estudiante_actualizar" onsubmit="return validarFormulario()">
            <?php echo campoTokenCSRF(); ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($estudiante['id'] ?? '') ?>">

            <div class="form-group">
                <label>Cédula (formato: 0-000-0000, 0-00-00 o 0-00-000):</label>
                <input type="text" id="cedula" name="cedula" value="<?= htmlspecialchars($estudiante['cedula'] ?? '') ?>" placeholder="Ej: 8-888-8888" required>
                <small class="hint">Ejemplos válidos: 8-888-8888, 8-88-88, 8-88-888</small>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($estudiante['nombre'] ?? '') ?>" required>
                <small class="hint">Solo letras. Primera letra mayúscula.</small>
            </div>

            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($estudiante['apellido']) ?>" required>
                <small class="hint">Solo letras. Primera letra mayúscula.</small>
            </div>

            <div class="form-group">
                <label>Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($estudiante['fecha_nacimiento'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Dirección:</label>
                <input type="text" name="direccion" value="<?= htmlspecialchars($estudiante['direccion']) ?>">
            </div>

            <div class="form-group">
                <label>Teléfono (formato: xxxx-xxxx):</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($estudiante['telefono']) ?>" placeholder="Ej: 1234-5678">
                <small class="hint">Formato: 4 dígitos, guión, 4 dígitos (ej: 1234-5678)</small>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($estudiante['email']) ?>" placeholder="ejemplo@correo.com">
                <small class="hint">Solo minúsculas. Debe contener @ y .com / .es</small>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Estudiante</button>
            <?php echo campoTokenCSRF(); // Campo oculto para el token CSRF ?>
        </form>
    </div>

    <script>
        function validarFormulario() {
            let errores = [];

            // 1. Validar cédula panameña
            let cedula = document.getElementById('cedula').value.trim();
            const regexCedula = /^[0-9]-[0-9]{2,3}-[0-9]{2,4}$/;
            if (!regexCedula.test(cedula)) {
                errores.push('❌ La cédula no tiene un formato válido. Usa: 0-000-0000, 0-00-00 o 0-00-000');
            }

            // 2. Validar nombre (solo letras y espacios, primera mayúscula)
            let nombre = document.getElementById('nombre').value.trim();
            if (!/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/.test(nombre)) {
                errores.push('❌ El nombre solo debe contener letras.');
            }
            if (nombre.length < 2) {
                errores.push('❌ El nombre debe tener al menos 2 caracteres.');
            }
            // Verificar que la primera letra sea mayúscula y el resto minúsculas
            let palabrasNombre = nombre.split(' ');
            for (let i = 0; i < palabrasNombre.length; i++) {
                if (palabrasNombre[i].length > 0) {
                    let p = palabrasNombre[i];
                    if (p[0] !== p[0].toUpperCase() || p.substring(1) !== p.substring(1).toLowerCase()) {
                        errores.push('❌ El nombre debe tener la primera letra mayúscula y el resto minúsculas.');
                        break;
                    }
                }
            }

            // 3. Validar apellido (solo letras y espacios, primera mayúscula)
            let apellido = document.getElementById('apellido').value.trim();
            if (!/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/.test(apellido)) {
                errores.push('❌ El apellido solo debe contener letras.');
            }
            if (apellido.length < 2) {
                errores.push('❌ El apellido debe tener al menos 2 caracteres.');
            }
            let palabrasApellido = apellido.split(' ');
            for (let i = 0; i < palabrasApellido.length; i++) {
                if (palabrasApellido[i].length > 0) {
                    let p = palabrasApellido[i];
                    if (p[0] !== p[0].toUpperCase() || p.substring(1) !== p.substring(1).toLowerCase()) {
                        errores.push('❌ El apellido debe tener la primera letra mayúscula y el resto minúsculas.');
                        break;
                    }
                }
            }

            // 4. Validar email (minúsculas, @ y .com/.es)
            let email = document.getElementById('email').value.trim();
            if (email !== '') {
                if (!/^[a-z0-9._%+-]+@[a-z0-9.-]+\.(com|es)$/i.test(email)) {
                    errores.push('❌ El correo debe ser válido (ej: usuario@dominio.com).');
                }
                if (email !== email.toLowerCase()) {
                    errores.push('❌ El correo debe estar en minúsculas.');
                }
            }

            // 5. Validar teléfono (formato xxxx-xxxx)
            let telefono = document.getElementById('telefono').value.trim();
            if (telefono !== '') {
                const regexTelefono = /^[0-9]{4}-[0-9]{4}$/;
                if (!regexTelefono.test(telefono)) {
                    errores.push('❌ El teléfono no tiene un formato válido. Usa: xxxx-xxxx');
                }
            }

            // Si hay errores, mostrarlos y cancelar envío
            if (errores.length > 0) {
                alert(errores.join('\n'));
                return false;
            }
            return true;
        }

        // ==========================================
        // AUTO-FORMATO PARA TELÉFONO (mientras se escribe)
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const telefonoInput = document.getElementById('telefono');
            if (telefonoInput) {
                telefonoInput.addEventListener('input', function(e) {
                    // Solo permitir dígitos
                    let valor = this.value.replace(/\D/g, '');
                    if (valor.length > 8) valor = valor.slice(0, 8);

                    // Aplicar formato xxxx-xxxx
                    if (valor.length > 4) {
                        valor = valor.slice(0, 4) + '-' + valor.slice(4);
                    }
                    this.value = valor;
                });
            }

            // ==========================================
            // EMAIL: Forzar minúsculas
            // ==========================================
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    this.value = this.value.toLowerCase().replace(/\s/g, '');
                });
            }

            // ==========================================
            // NOMBRE Y APELLIDO: Formato automático
            // ==========================================
            function formatearNombre(campoId) {
                const campo = document.getElementById(campoId);
                if (campo) {
                    campo.addEventListener('input', function() {
                        // Eliminar caracteres inválidos
                        this.value = this.value.replace(/[^a-zA-ZáéíóúñÁÉÍÓÚÑ\s]/g, '');
                        // Capitalizar primera letra de cada palabra
                        let palabras = this.value.toLowerCase().split(' ');
                        for (let i = 0; i < palabras.length; i++) {
                            if (palabras[i].length > 0) {
                                palabras[i] = palabras[i][0].toUpperCase() + palabras[i].substring(1);
                            }
                        }
                        this.value = palabras.join(' ');
                    });
                }
            }

            formatearNombre('nombre');
            formatearNombre('apellido');
        });
    </script>
</body>
</html>