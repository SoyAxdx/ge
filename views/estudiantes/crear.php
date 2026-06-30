<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Estudiante - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container" style="max-width:600px;">
        <div class="dashboard-header">
            <h1>➕ Nuevo Estudiante</h1>
            <a href="index.php?action=estudiantes" class="btn btn-secondary" style="display:inline-block; width:auto; padding:5px 15px;">← Volver</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form id="formEstudiante" method="POST" action="index.php?action=estudiante_guardar" onsubmit="return validarFormulario()">
            <?php echo campoTokenCSRF(); ?>
            <div class="form-group">
                <label>Cédula (formato: 0-000-0000, 0-00-00 o 0-00-000):</label>
                <input type="text" id="cedula" name="cedula" placeholder="Ej: 8-888-8888" required>
                <small style="color:#888; font-size:12px;">Ejemplos válidos: 8-888-8888, 8-88-88, 8-88-888</small>
            </div>

            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="apellido" required>
            </div>

            <div class="form-group">
                <label>Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento">
            </div>

            <div class="form-group">
                <label>Dirección:</label>
                <input type="text" name="direccion">
            </div>

            <div class="form-group">
                <label>Teléfono (formato: xxxx-xxxx):</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ej: 1234-5678">
                <small style="color:#888; font-size:12px;">Formato: 4 dígitos, guión, 4 dígitos (ej: 1234-5678)</small>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email">
            </div>

            <button type="submit" class="btn btn-primary">Guardar Estudiante</button>
            <?php echo campoTokenCSRF(); // Campo oculto para el token CSRF ?>
        </form>
    </div>

    <script>
        function validarFormulario() {
            let cedula = document.getElementById('cedula').value.trim();
            let telefono = document.getElementById('telefono').value.trim();
            let errores = [];

            // Validar cédula panameña
            const regexCedula = /^[0-9]-[0-9]{2,3}-[0-9]{2,4}$/;
            if (!regexCedula.test(cedula)) {
                errores.push('❌ La cédula no tiene un formato válido. Usa: 0-000-0000, 0-00-00 o 0-00-000');
            }

            // Validar teléfono (si se ingresa)
            if (telefono !== '') {
                const regexTelefono = /^[0-9]{4}-[0-9]{4}$/;
                if (!regexTelefono.test(telefono)) {
                    errores.push('❌ El teléfono no tiene un formato válido. Usa: xxxx-xxxx');
                }
            }

            if (errores.length > 0) {
                alert(errores.join('\n'));
                return false;
            }
            return true;
        }
    </script>
</body>
</html>