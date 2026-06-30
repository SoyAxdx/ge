<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Nota - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php if (session_status() === PHP_SESSION_NONE) { session_start(); } $inscripcion = $inscripcion ?? []; ?>
    <div class="dashboard-container" style="max-width:600px;">
        <div class="dashboard-header">
            <h1>✏️ Editar Nota</h1>
            <a href="index.php?action=inscripciones" class="btn btn-secondary" style="display:inline-block; width:auto; padding:5px 15px;">← Volver</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=inscripcion_actualizar">
            <?php echo campoTokenCSRF(); ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($inscripcion['id'] ?? '') ?>">

            <div class="form-group">
                <label>Estudiante:</label>
                <input type="text" value="<?= htmlspecialchars( ($inscripcion['estudiante_nombre'] ?? '') . ' ' . ($inscripcion['estudiante_apellido'] ?? '') ) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Curso:</label>
                <input type="text" value="<?= htmlspecialchars($inscripcion['curso_nombre'] ?? '') ?>" disabled>
            </div>

            <div class="form-group">
                <label>Nota Final (0-100):</label>
                <input type="number" name="nota_final" step="0.01" min="0" max="100" value="<?= $inscripcion['nota_final'] ?? '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Nota</button>
            <?php echo campoTokenCSRF(); // Campo oculto para el token CSRF ?>
        </form>
    </div>
</body>
</html>