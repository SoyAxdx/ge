<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Inscripción - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container" style="max-width:600px;">
        <div class="dashboard-header">
            <h1>➕ Nueva Inscripción</h1>
            <a href="index.php?action=inscripciones" class="btn btn-secondary" style="display:inline-block; width:auto; padding:5px 15px;">← Volver</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=inscripcion_guardar">
            <div class="form-group">
                <label>Estudiante:</label>
                <select name="estudiante_id" required>
                    <option value="">-- Seleccionar Estudiante --</option>
                    <?php foreach ($estudiantes as $e): ?>
                        <option value="<?= $e['id'] ?>">
                            <?= htmlspecialchars($e['cedula'] . ' - ' . $e['nombre'] . ' ' . $e['apellido']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Curso:</label>
                <select name="curso_id" required>
                    <option value="">-- Seleccionar Curso --</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['id'] ?>">
                            <?= htmlspecialchars($c['codigo'] . ' - ' . $c['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Inscribir Estudiante</button>
        </form>
    </div>
</body>
</html>